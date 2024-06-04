<?php
if (isset($_SESSION["logged_in"])) {


    header("Location: login.php");

    die();
}
session_start();

require 'database.php';

?>
<?php include 'components/header_app.php'; ?>



<section>
    <?php 
    if (isset($_SESSION["message"])) {
        echo $_SESSION["message"];
        unset($_SESSION["message"]);
    }


    ?>


    <div>
        <form action="add_habit_form.php" method="post">
            <div class="form-group">
                <input type="text" name="habit_name" class="form-control" placeholder="Habit" required>

                <select name="habit_type" class="form-control" required>
                    <option value="1">Good</option>
                    <option value="2">Bad</option>
                </select>

                <input type="submit" name="submit" class="btn btn-primary" value="Add habit">
            </div>
        </form>
    </div>
    <div class="grid-container">
        <?php
        // Pobieranie user_id z sesji lub z URL
        $user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : (isset($_GET['userid']) ? $_GET['userid'] : null);
        if ($user_id) {
            // Wywołanie nawyków przypisanych do konkretnego user_id
            $sql = "SELECT habits.* FROM user_habits JOIN habits ON user_habits.habit_id = habits.id WHERE user_habits.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $done_habits = [];
            $undone_habits = [];

            while ($row = $result->fetch_assoc()) {
                // Sprawdź, czy nawyk jest zrobiony
                $sql_done = "SELECT * FROM habit_data WHERE userid = ? AND habitid = ? AND date = CURDATE()";
                $stmt_done = $conn->prepare($sql_done);
                $stmt_done->bind_param("ii", $user_id, $row['id']);
                $stmt_done->execute();
                $result_done = $stmt_done->get_result();

                if ($result_done->num_rows > 0) {
                    $done_habits[] = $row;
                } else {
                    $undone_habits[] = $row;
                }

                $stmt_done->close();
            }

            $stmt->close();
            echo "<a href='/habits.php'>Zobacz wszystkie nawyki</a>";

            echo "<h2>Zrobione nawyki:</h2>";
            foreach ($done_habits as $habit) {
                echo "<div class='grid-item alert alert-info'> <a href='singlehabit.php?habit_id=" . $habit['id'] . "'>" . $habit['habit_name'] . " </a>
            <form action=\"delete_habit.php\" method=\"post\">
            <input type=\"hidden\" name=\"habit_id\" value=\"" . $habit['id'] . "\">
            <input type=\"submit\" class=\"btn btn-danger\" value=\"Leave habit\"></form> 
            <form action=\"habit_reset.php\" method=\"post\">
            <input type=\"hidden\" name=\"habit_id\" value=\"" . $habit['id'] . "\">
            <input type=\"submit\" class=\"btn btn-danger\" value=\"Reset\"></form>
          
            </div>";
            }

            echo "<h2>Niezrobione nawyki:</h2>";
            foreach ($undone_habits as $habit) {
                echo "<div class='grid-item alert alert-info'> <a href='singlehabit.php?habit_id=" . $habit['id'] . "'>" . $habit['habit_name'] . " </a>
            <form action=\"delete_habit.php\" method=\"post\">
            <input type=\"hidden\" name=\"habit_id\" value=\"" . $habit['id'] . "\">
            <input type=\"submit\" class=\"btn btn-danger\" value=\"Leave habit\"></form> 
            
            <form action=\"done_habit.php\" method=\"post\">
            <input type=\"hidden\" name=\"habit_id\" value=\"" . $habit['id'] . "\">
            <input type=\"submit\" class=\"btn btn-success\" value=\"Zrobione\"></form>
            </div>";
            }

        } else {
            echo "Brak user_id.";
        }

        // Zamknięcie połączenia z bazą danych
        $conn->close();
        ?>
    </div>



</section>



<?php include 'components/footer_app.php'; ?>