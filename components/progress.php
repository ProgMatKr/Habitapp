



<div class="grid-container">
    <?php
    // Pobierz id zalogowanego użytkownika z sesji
    $user_id = $_SESSION['userid'];

    // Pobierz bieżącą datę w formacie RRRR-MM-DD
    $date = date('Y-m-d');

    // Tworzymy zapytanie SQL, aby pobrać wszystkie nawyki przypisane do zalogowanego użytkownika
    $sql = "SELECT habits.* FROM habits 
            LEFT JOIN user_habits ON habits.id = user_habits.habit_id 
            WHERE user_habits.user_id = $user_id";
    
    // Wykonujemy zapytanie SQL i przechowujemy wynik w zmiennej $result
    $result = mysqli_query($conn, $sql);

    // Tworzymy zapytanie SQL, aby sprawdzić, czy są jakieś nawyki, które nie zostały jeszcze zrealizowane dzisiaj
    $sql_unfinished = "SELECT habits.id FROM habits
                       LEFT JOIN user_habits ON habits.id = user_habits.habit_id
                       LEFT JOIN habit_data ON habits.id = habit_data.habitid 
                       AND habit_data.userid = $user_id AND habit_data.date = '$date'
                       WHERE user_habits.user_id = $user_id AND habit_data.habitid IS NULL";
    
    // Wykonujemy zapytanie SQL i przechowujemy wynik w zmiennej $unfinished_result
    $unfinished_result = mysqli_query($conn, $sql_unfinished);

    // Sprawdzamy, czy są nawyki, które nie zostały jeszcze zrealizowane
    if ($unfinished_result && mysqli_num_rows($unfinished_result) == 0) {
        echo '<div class="p-4 text-center text-gray-600"><p>Congratulations! You have completed all your habits for today!</p></div>';
    } else {
        // Iterujemy przez każdy wiersz wyniku zapytania (każdy wiersz reprezentuje jeden nawyk)
        while ($row = mysqli_fetch_assoc($result)) {
            // Tworzymy zapytanie SQL, aby sprawdzić, czy dany nawyk został zrealizowany dzisiaj przez użytkownika
            $sql_check = "SELECT * FROM habit_data 
                          WHERE userid = $user_id AND habitid = " . $row['id'] . " AND `date` = '$date'";
            
            // Wykonujemy zapytanie SQL i przechowujemy wynik w zmiennej $czy_jest
            $czy_jest = mysqli_query($conn, $sql_check);

            // Sprawdzamy, czy w wyniku zapytania są jakieś wiersze, co oznacza, że nawyk został zrealizowany
            // Jeśli tak, ustawiamy zmienną $czy_zrobiona na 1, w przeciwnym razie na 0
            $czy_zrobiona = (mysqli_num_rows($czy_jest) > 0) ? 1 : 0;

            // Przygotowanie znacznika „Done” tylko, gdy nawyk został zrealizowany
            $done_label = ($czy_zrobiona == 1) ? "<div class='absolute bg-green-500 rounded-r p-2 rounded-bl-lg rounded-tr-lg text-white font-semibold right-0 top-0'><a>Done</a></div>" : "";

            // Wyświetlamy formularz dla każdego nawyku
            echo "
                <div class='relative rounded-lg shadow-lg grid-item alert alert-info p-4'>
                    <!-- Link do szczegółów nawyku -->
                    <a href='singlehabit.php?habit_id=" . $row['id'] . "' class='block font-semibold uppercase mb-2'>" . $row['habit_name'] . "</a>
                    
                    <!-- Znacznik „Done” wyświetlany tylko, gdy nawyk został zrealizowany -->
                    $done_label
            ";

            // Wyświetlamy formularz "Zrobione" tylko, gdy nawyk nie został zrealizowany
            if ($czy_zrobiona == 0) {
                echo "
                    <form action=\"done_habit.php\" method=\"post\" class='mb-2'>
                        <!-- Ukryte pole zawierające id nawyku -->
                        <input type=\"hidden\" name=\"habit_id\" value=\"" . $row['id'] . "\">
                        <!-- Przycisk do oznaczania nawyku jako wykonanego -->
                        <input type=\"submit\" class=\"btn btn-success w-full\" value=\"Zrobione\">
                    </form>
                ";
            }

            // Wyświetlamy formularz do resetowania nawyku tylko, gdy nawyk został zrealizowany
            if ($czy_zrobiona == 1) {
                echo "
                    <form action='habit_reset.php' method=\"post\">
                        <!-- Ukryte pole zawierające id nawyku -->
                        <input type=\"hidden\" name=\"habit_id\" value=\"" . $row['id'] . "\">
                        <!-- Ukryte pole zawierające URL bieżącej strony, aby wrócić po operacji -->
                        <input type=\"hidden\" name=\"return_url\" value=\"" . $_SERVER['REQUEST_URI'] . "\">
                        <!-- Przycisk do resetowania nawyku -->
                        <input type=\"submit\" class=\"btn btn-danger w-full\" value=\"Reset habit\">
                    </form>
                ";
            }
            echo "</div>";
        }
    }
    ?>
</div>
