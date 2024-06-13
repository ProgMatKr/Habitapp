<?php

require 'database.php';
session_start();

// Pobierz id zalogowanego użytkownika
$user_id = $_SESSION['userid'];

// Pobierz id nawyku z formularza
$habit_id = $_POST['habit_id'];

// Ustaw datę na dzisiejszą datę
$date = date("Y-m-d");

// Sprawdź, czy pobrane dane są prawidłowe
if (!isset($user_id, $habit_id)) {
    $_SESSION['message'] = "<div class='danger_message'>Invalid data provided.</div>";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

// Ustaw dzisiejszą datę
$today = new DateTime($date);

// Wczorajsza data
$yesterday = clone $today;
$yesterday->sub(new DateInterval('P1D'));
$formatted_yesterday = $yesterday->format('Y-m-d');

// Sprawdzenie, czy nawyk był wykonany wczoraj
$sql = "SELECT done_in_row FROM user_habits 
        WHERE user_id = ? AND habit_id = ? AND last_done_date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $habit_id, $formatted_yesterday);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

$sql_doneinrow = "SELECT done_in_row FROM user_habits 
        WHERE user_id = ? AND habit_id = ?";
$stmt_doneinrow = $conn->prepare($sql_doneinrow );
$stmt_doneinrow->bind_param("ii", $user_id, $habit_id);
$stmt_doneinrow->execute();
$result_doneinrow = $stmt_doneinrow->get_result();



$done_in_row = $result_doneinrow; // Domyślnie zakładamy, że to pierwszy dzień streaku

if ($result_doneinrow->num_rows > 0) {
    $row = $result_doneinrow->fetch_assoc();
    $last_done_date = $row['last_done_date'];
    $current_done_in_row = $row['done_in_row'];

    // Sprawdzenie, czy nawyk był wykonany wczoraj
    if ($last_done_date === $formatted_yesterday) {
        // Zwiększamy streak o 1
        $done_in_row = $current_done_in_row + 1;
    } else {
        // Resetujemy streak, jeśli nawyk nie był wykonany dzień po dniu
        $done_in_row = 1;
    }
}

// Aktualizacja done_in_row i last_done_date
$sql = "UPDATE user_habits SET done_in_row = ?, last_done_date = ? 
        WHERE user_id = ? AND habit_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isii", $done_in_row, $date, $user_id, $habit_id);
$stmt->execute();
$stmt->close();

// Wstawienie nowego wpisu do tabeli habit_data
$sql = "INSERT INTO habit_data (userid, habitid, date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $habit_id, $date);
$stmt->execute();

// Sprawdzenie, czy dane zostały pomyślnie dodane
if ($stmt->affected_rows > 0) {
    $_SESSION['message'] = "<div class='success_message'>Congratulations! Habit recorded successfully.</div>";
} else {
    $_SESSION['message'] = "<div class='danger_message'>Something went wrong. Please try again.</div>";
}
$stmt->close();

// Przekierowanie z powrotem na poprzednią stronę
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;

?>
