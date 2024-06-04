<?php

session_start();
require_once "database.php";

// Pobierz id zalogowanego użytkownika
$user_id = $_SESSION['userid'];



// Pobierz dane nawyku z formularza
$habit_name = $_POST['habit_name'];

// Sprawdź, czy nawyk o tej samej nazwie już istnieje dla tego użytkownika
$stmt = $conn->prepare("SELECT * FROM habits WHERE habit_name = ?");
$stmt->bind_param("s", $habit_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nawyk o tej samej nazwie już istnieje
    $_SESSION['message'] = "<div class='danger_message'>Habit already exists</div>";
    header("Location: /myhabits.php");
    exit;
}

// Przygotuj zapytanie SQL
$stmt = $conn->prepare("INSERT INTO habits (habit_name) VALUES (?)");

// Powiąż parametry zapytania z danymi nawyku
$stmt->bind_param("s", $habit_name);

// Wykonaj zapytanie
$stmt->execute();

// Pobierz id dodanego nawyku
$habit_id = $stmt->insert_id;

// Dodaj nawyk do użytkownika w tabeli user_habits
$stmt = $conn->prepare("INSERT INTO user_habits (user_id, habit_id) VALUES (?, ?)");
$stmt->bind_param("ii", $user_id, $habit_id);
$stmt->execute();

// Sprawdź, czy nawyk został pomyślnie dodany
if ($stmt->affected_rows > 0) {
    $_SESSION['message'] = "<div class='success_message'>Habit added successfully</div>";
} else {
    $_SESSION['message'] = "<div class='danger_message'>Error adding habit</div>";
}

header("Location: /myhabits.php");
exit;

// Zamknij połączenie
$conn->close();
?>