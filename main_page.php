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

    <h3 class="p-2 text-xl">My habits: </h3>
    <?php include 'components/undone_habits.php'; ?>
</div>



<div class="p-2">

    <h3 class="p-2 text-xl">My Progress:</h3>


    
</div>













<?php include 'components/footer_app.php'; ?>