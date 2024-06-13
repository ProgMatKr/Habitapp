<?php
// Pobieranie user_id z sesji lub z URL
$user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : (isset($_GET['userid']) ? $_GET['userid'] : null);
if ($user_id) {
    // Wywołanie nawyków przypisanych do konkretnego user_id
    $sql = "SELECT habits.* FROM user_habits JOIN habits ON user_habits.habit_id = habits.id WHERE user_habits.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $habits = $stmt->get_result();

    $stmt->close();
    echo "<div class='max-w-2xl'>";
    echo "<a href='/habits.php' class='text-indigo-600 hover:underline mb-4 inline-block'>Zobacz wszystkie nawyki</a>";

    echo "<h2 class='text-2xl font-semibold text-gray-700 mb-4'>My habits:</h2>";
    echo "<div class='grid gap-1 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'>";
    foreach ($habits as $habit) {
        echo "
                <a href='singlehabit.php?habit_id=" . $habit['id'] . "' class='text-lg bg-white p-6 uppercase rounded-lg shadow-lg flex flex-col items-start font-medium text-gray-700 hover:underline mb-2'>" . $habit['habit_name'] . "</a>";
    }
    echo "</div>";
    echo "</div>";
} else {
    echo "<div class='text-center text-red-600 font-semibold'>Brak user_id.</div>";
}

// Zamknięcie połączenia z bazą danych
?>