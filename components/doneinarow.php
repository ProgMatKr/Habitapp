<?php
// Zakładamy, że zmienne $conn, $user_id, $habit_id i $date są już ustawione

// Obecna data
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

// Zakładamy, że streak ma domyślną wartość 1 (dzisiejszy dzień)
$done_in_row = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $done_in_row = $row['done_in_row'] + 1; // Zwiększamy streak o 1
} 

// Aktualizacja danych w tabeli user_habits
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
$stmt->close();

echo "Nawyk został zaktualizowany, streak wynosi $done_in_row dni.";
?>
