<?php

session_start();
require_once "database.php";

if (!isset($_SESSION['userid']) || !isset($_POST['habit_id'])) {
    $_SESSION['message'] = "<div class='danger_message'>Brak wymaganych danych</div>";
    header("Location: /habits.php");
    exit;
}

$user_id = $_SESSION['userid']; // Pobierz id zalogowanego użytkownika
$habit_id = $_POST['habit_id']; // Pobierz id nawyku z formularza

// Sprawdź czy nawyk jest aktualnie przypisany do użytkownika
$stmt = $conn->prepare("SELECT * FROM user_habits WHERE user_id = ? AND habit_id = ?");
$stmt->bind_param("ii", $user_id, $habit_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['message'] = "<div class='danger_message'>Nawyk już przypisany</div>";
} else {
    // jeśli nie to dodaj nawyk do użytkownika 
    $stmt = $conn->prepare("INSERT INTO user_habits (user_id, habit_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $habit_id);
    $stmt->execute();

    // Sprawdź, czy nawyk został pomyślnie dodany
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "<div class='success_message'>Habit added successfully, Good luck!</div>";
    } else {
        $_SESSION['message'] = "<div class='danger_message'>Error adding habit</div>";
    }
}

$stmt->close();
$conn->close();

header("Location: /myhabits.php");
exit;

?>