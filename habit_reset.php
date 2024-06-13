<?php

session_start();
require 'database.php';

// Pobierz id zalogowanego użytkownika
$user_id = $_SESSION['userid'];

// Pobierz id nawyku z formularza
$habit_id = $_POST['habit_id'];

// Przygotuj zapytanie SQL
$stmt = $conn->prepare("DELETE FROM habit_data WHERE habitid = ? AND userid = ?");

// Powiąż parametry zapytania z id nawyku i id użytkownika
$stmt->bind_param("ii", $habit_id, $user_id);

// Wykonaj zapytanie
$stmt->execute();

$sql_streak = "SELECT done_in_row FROM user_habits WHERE user_id = ? AND habit_id = ?";
$stmt_streak = $conn->prepare($sql_streak);
$stmt_streak->bind_param("ii", $user_id, $habit_id);
$stmt_streak->execute();
$streak_result = $stmt_streak->get_result();

if ($streak_result->num_rows > 0) {
    $row = $streak_result->fetch_assoc();
    $done_in_row = $row['done_in_row'];

    // Zmniejsz done_in_row o jeden, jeśli jest większe od 0
    if ($done_in_row > 0) {
        $done_in_row--;
    }

    // Zaktualizuj wartość done_in_row w tabeli user_habits
    $stmt_update = $conn->prepare("UPDATE user_habits SET done_in_row = ? WHERE user_id = ? AND habit_id = ?");
    $stmt_update->bind_param("iii", $done_in_row, $user_id, $habit_id);
    $stmt_update->execute();
    $stmt_update->close();
}

// Sprawdź, czy nawyk został pomyślnie zresetowany
if ($stmt->affected_rows > 0) {

    $_SESSION['message'] = "<div class='success_message'>Habit reset successfully</div>";
} else {
    
    $_SESSION['message'] = "<div class='danger_message'>Error resetting habit</div>";
}
header("Location: " . $_SERVER['HTTP_REFERER']);

exit;

// Zamknij połączenie

?>