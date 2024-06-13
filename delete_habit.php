<?php

session_start();
require 'database.php';

// Sprawdź, czy sesja jest uruchomiona i zawiera user_id
if (!isset($_SESSION['userid'])) {
    die("User ID is not set in the session.");
}

// Pobierz id zalogowanego użytkownika
$user_id = $_SESSION['userid'];

// Sprawdź, czy habit_id jest ustawiony w danych POST
if (!isset($_POST['habit_id'])) {
    die("Habit ID is not set in the POST data.");
}

// Pobierz id nawyku z formularza
$habit_id = $_POST['habit_id'];

// Debugging: Sprawdź wartości zmiennych
echo "User ID: " . htmlspecialchars($user_id) . "<br>";
echo "Habit ID: " . htmlspecialchars($habit_id) . "<br>";

// Przygotuj zapytanie SQL
$stmt = $conn->prepare("DELETE FROM `user_habits` WHERE `user_id` = ? AND `habit_id` = ?");
$stmt2 = $conn->prepare("DELETE FROM `habit_data` WHERE `userid` = ? AND `habitid` = ?");
$stmt2->bind_param("ii", $user_id, $habit_id);

$stmt2->execute();


// Powiąż parametry zapytania z id użytkownika i id nawyku
$stmt->bind_param("ii", $user_id, $habit_id);

// Wykonaj zapytanie
if ($stmt->execute()) {
    // Sprawdź, czy nawyk został pomyślnie zresetowany
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "<div class='success_message'>You left the habit successfully</div>";
    } else {
        $_SESSION['message'] = "<div class='danger_message'>Error: No rows affected. User ID: $user_id, Habit ID: $habit_id</div>";
    }
} else {
    $_SESSION['message'] = "<div class='danger_message'>SQL Error: " . $stmt->error . "</div>";
}

// Przekierowanie użytkownika
header("Location: /myhabits.php");
exit;

// Zamknij połączenie
$stmt->close();

?>
