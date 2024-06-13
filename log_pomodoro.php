<?php
session_start();
require 'database.php';

// Pobierz user_id z sesji lub żądania POST
$user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : (isset($_POST['user_id']) ? $_POST['user_id'] : null);

if ($user_id) {
    // Aktualizacja liczby wykonanych sesji pomodoro
    $sql = "UPDATE users SET wykonanych_pomodoro = wykonanych_pomodoro + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
    
    echo "Session logged successfully!";
} else {
    echo "User ID not found.";
}

?>
