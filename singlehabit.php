<?php

require 'database.php';


require_once "database.php";





if (isset($_SESSION["logged_in"])) {

    header("Location: add_habit.php");

    die();
}

?>


<?php include 'components/header_app.php'; ?>

<?php



$habit_id = $_GET['habit_id'];

$stmt = $conn->prepare("SELECT habit_name FROM habits WHERE id = ?");

$stmt->bind_param("i", $habit_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Pobranie danych o nawyku
    $row = $result->fetch_assoc();
    $habit_name = $row['habit_name']; 

    // Wyświetlanie informacji o nawyku
    echo "<p class='mb-2'><strong>$habit_name</strong></p>";
} else {
    echo "<p>Nie znaleziono nawyku o id:  $habit_id</p>";
}

// Zamknięcie połączenia
$conn->close();

?>

<!-- Dodaj przycisk "Dodaj nawyk" -->
<a href="add_habit_form.php" class="btn btn-primary">Dodaj nawyk</a>




<?php include 'components/footer_app.php'; ?>