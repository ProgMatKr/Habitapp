<?php

require 'database.php';

// Pobierz id zalogowanego użytkownika z sesji
$user_id = $_SESSION['userid'];

// Pobierz bieżącą datę w formacie RRRR-MM-DD
$date = date('Y-m-d');

// Przygotowanie zapytania SQL do pobrania nawyków użytkownika
$sql = "SELECT habits.id, habits.habit_name 
        FROM habits 
        JOIN user_habits ON habits.id = user_habits.habit_id 
        WHERE user_habits.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

echo '<div class="grid-container">';
while ($row = $result->fetch_assoc()) {
    $habit_id = $row['id'];
    $habit_name = $row['habit_name'];

    // Sprawdzenie, czy nawyk został zrealizowany dzisiaj przez użytkownika
    $sql_check = "SELECT * 
                  FROM habit_data 
                  WHERE userid = ? AND habitid = ? AND `date` = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("iis", $user_id, $habit_id, $date);
    $stmt_check->execute();
    $czy_jest = $stmt_check->get_result();
    $stmt_check->close();

    $czy_zrobiona = ($czy_jest->num_rows > 0) ? 1 : 0;

    // Przygotowanie znacznika „Done” tylko, gdy nawyk został zrealizowany
    $done_label = ($czy_zrobiona == 1) ? "<div class='absolute bg-green-500 rounded-r p-2 rounded-bl-lg rounded-tr-lg text-white font-semibold right-0 top-0'><a>Done</a></div>" : "";

    echo "<div class='relative rounded-lg shadow-lg grid-item alert alert-info p-4'>";
    echo "<a href='singlehabit.php?habit_id=$habit_id' class='block font-semibold uppercase mb-2'>$habit_name</a>";
    echo $done_label;

    if ($czy_zrobiona == 0) {
        echo "<form action='done_habit.php' method='post' class='mb-2'>
                  <input type='hidden' name='habit_id' value='$habit_id'>
                  <input type='submit' class='btn font-semibold bg-gray-100 btn-success w-full' value='Done'>
              </form>";
    }

    if ($czy_zrobiona == 1) {
        echo "<form class='flex justify-end' action='habit_reset.php' method='post'>
                  <input type='hidden' name='habit_id' value='$habit_id'>
                  <input type='hidden' name='return_url' value='" . $_SERVER['REQUEST_URI'] . "'>
                  <input type='submit' class='btn btn-danger font-semibold p-2' value='Reset'>
              </form>";
    }

    // Pobranie streaka dla każdego nawyku użytkownika
    $sql_streak = "SELECT done_in_row 
                   FROM user_habits 
                   WHERE user_id = ? AND habit_id = ?";
    $stmt_streak = $conn->prepare($sql_streak);
    $stmt_streak->bind_param("ii", $user_id, $habit_id);
    $stmt_streak->execute();
    $streak_result = $stmt_streak->get_result();
    $stmt_streak->close();

    if ($streak_result->num_rows > 0) {
        $streak_row = $streak_result->fetch_assoc();
        $streak = $streak_row['done_in_row'];
        echo "<p>Streak: $streak days in a row.</p>";
    }

    echo "</div>";
}
echo '</div>';

?>
