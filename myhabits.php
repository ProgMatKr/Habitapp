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

    
    <?php include '\habitapp\done_undone.php'; ?>

    </div>



</section>



<?php include 'components/footer_app.php'; ?>