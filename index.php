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





<?php include 'components/progress_bar.php'; ?>


<div class="p-2">
<a href="/myhabits.php" >
<img  width="40px" height="auto" src="images\add.svg">
    
</a>

</div>








<?php include 'components/progress.php'; ?>






<div class="h-20"></div>







<?php include 'components/footer_app.php'; ?>