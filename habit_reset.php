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

// Sprawdź, czy nawyk został pomyślnie zresetowany
if ($stmt->affected_rows > 0) {

    $_SESSION['message'] = "<div class='success_message'>Habit reset successfully</div>";
} else {
    
    $_SESSION['message'] = "<div class='danger_message'>Error resetting habit</div>";
}

header("Location: /myhabits.php");
exit;

// Zamknij połączenie
$conn->close();

?>