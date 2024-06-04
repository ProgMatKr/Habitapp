<?php

session_start();
require 'database.php';

// Pobierz id zalogowanego użytkownika
$user_id = $_SESSION['userid'];

// Pobierz id nawyku z formularza
$habit_id = $_POST['habit_id'];

// Przygotuj zapytanie SQL do usunięcia nawyku z tabeli user_habits
$stmt = $conn->prepare("DELETE FROM user_habits WHERE habit_id = ? AND user_id = ?");

// Powiąż parametry zapytania z id nawyku i id użytkownika
$stmt->bind_param("ii", $habit_id, $user_id);

// Wykonaj zapytanie
$stmt->execute();

// Sprawdź, czy nawyk został pomyślnie usunięty
if ($stmt->affected_rows > 0) {
    // Jeśli tak, usuń również dane nawyku z tabeli habit_data
    $stmt2 = $conn->prepare("DELETE FROM habit_data WHERE habitid = ? AND userid = ?");
    $stmt2->bind_param("ii", $habit_id, $user_id);
    $stmt2->execute();
    $stmt2->close();

    $_SESSION['message'] = "<div class='success_message'>Habit deleted successfully</div>";
} else {
    $_SESSION['message'] = "<div class='danger_message'>Error deleting habit</div>";
}

header("Location: /myhabits.php");
exit;

// Zamknij połączenie
$conn->close();

?>