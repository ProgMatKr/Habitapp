<?php

session_start();

require_once "database.php";






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

?>

<!-- Dodaj przycisk "Dodaj nawyk" -->
                <form action='delete_habit.php' method='post' class='w-full'>
                <input type='hidden' name='habit_id' value='<?php echo $habit_id; ?>'>
                <input type='submit' class='mt-auto px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 w-full' value='Leave habit'>
                </form>
                


<?php include 'components/footer_app.php'; ?>