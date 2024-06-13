<?php
// Pobierz id zalogowanego użytkownika z sesji
$user_id = $_SESSION['userid'];

// Pobierz bieżącą datę w formacie RRRR-MM-DD
$date = date('Y-m-d');

// Tworzymy zapytanie SQL, aby pobrać liczbę nawyków przypisanych do zalogowanego użytkownika
$sql_total_habits = "SELECT COUNT(*) as total FROM habits 
                     LEFT JOIN user_habits ON habits.id = user_habits.habit_id 
                     WHERE user_habits.user_id = $user_id";

// Wykonujemy zapytanie SQL i przechowujemy wynik w zmiennej $result_total
$result_total = mysqli_query($conn, $sql_total_habits);
$row_total = mysqli_fetch_assoc($result_total);
$total_habits = $row_total['total']; // Liczba nawyków przypisanych

// Tworzymy zapytanie SQL, aby pobrać liczbę nawyków zrealizowanych dzisiaj przez użytkownika
$sql_done_habits = "SELECT COUNT(*) as done FROM habit_data 
                    WHERE userid = $user_id AND `date` = '$date'";

// Wykonujemy zapytanie SQL i przechowujemy wynik w zmiennej $result_done
$result_done = mysqli_query($conn, $sql_done_habits);
$row_done = mysqli_fetch_assoc($result_done);
$done_habits = $row_done['done']; // Liczba nawyków zrealizowanych

// Obliczamy procentowy postęp
$progress_percent = $total_habits > 0 ? ($done_habits / $total_habits) * 100 : 0;
$progress_percent = round($progress_percent, 2); // Zaokrąglamy do dwóch miejsc po przecinku

?>


<div class="progress-container">
        <div class="progress-bar" style="width: <?php echo $progress_percent; ?>%;">
            <?php echo $progress_percent; ?>%
        </div>
    </div>
 
