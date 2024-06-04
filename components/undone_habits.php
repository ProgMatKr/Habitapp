<?php
$database = require_once "database.php";

$user_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : (isset($_GET['userid']) ? $_GET['userid'] : null);

if ($user_id) {
    // Wywołanie nawyków przypisanych do konkretnego user_id, które nie są w tabeli habit_data
    $sql = "SELECT habits.* FROM user_habits JOIN habits ON user_habits.habit_id = habits.id WHERE user_habits.user_id = ? AND habits.id NOT IN (SELECT habitid FROM habit_data WHERE userid = ? AND date = CURDATE())";    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();


    echo "<div class='grid-container'>";

    while ($row = $result->fetch_assoc()) {
        echo 
        "<div class='grid-item alert alert-info '> <a href='singlehabit.php?habit_id=" . $row['id'] . "'>" . $row['habit_name'] . "</a>
            <form action=\"done_habit.php\" method=\"post\">
                <input type=\"hidden\" name=\"habit_id\" value=\"" . $row['id'] . "\">
                <input type=\"submit\" class=\"btn btn-success\" value=\"Zrobione\">
            </form>
        </div>";
    }

    echo "</div>";
    
    $stmt->close();
} else {
    echo "Brak user_id.";
}
?>