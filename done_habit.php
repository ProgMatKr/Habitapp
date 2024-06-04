<?php

require 'database.php';

session_start();

// Pobierz id zalogowanego użytkownika
$user_id = $_SESSION['userid'];

// Pobierz id nawyku z formularza
$habit_id = $_POST['habit_id'];

// Ustaw datę na dzisiejszą datę
$date = date("Y-m-d");

// Przygotuj zapytanie SQL
$stmt = $conn->prepare("INSERT INTO habit_data (userid, habitid, date) VALUES (?, ?, ?)");

// Powiąż parametry zapytania z danymi
$stmt->bind_param("iis", $user_id, $habit_id, $date);

// Wykonaj zapytanie
$stmt->execute();

// Sprawdź, czy dane zostały pomyślnie dodane
    if ($stmt->affected_rows > 0) {
        $_SESSION['message'] = "<div class='success_message'>Congratzzz</div>";
    } else {
        $_SESSION['message'] = "<div class='danger_message'>Something happened wrong</div>";
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;

// Zamknij połączenie
$conn->close();

?>