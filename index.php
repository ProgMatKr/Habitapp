<?php

session_start();


require 'database.php';



if (!isset($_SESSION["logged_in"])) {


    header("Location: login.php");

    die();
}





?>



<?php include 'components/header_app.php'; ?>




<?php include 'components/welcome.php'; ?>








<div class="p-2">


<?php include 'components/undone_habits.php'; 


if (empty($undone_habits)) {
    echo '<h2 class="text-lg font-semibold">Congratz, You\'ve done very good work today!</h2>';
} else {
    echo '<h2 class="text-lg font-semibold">You haven\'t finish for today! Hurry up!</h2>';
}

?>




</div>















<?php include 'components/footer_app.php'; ?>