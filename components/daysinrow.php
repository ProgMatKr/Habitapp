<?php 
require_once "database.php"; 
?>

<nav>
  <ul style="position:relative;">
    <li >
      <?php 
      if (isset($_SESSION["days_streak"])) {
        echo '<img  width="40px" height="auto" src="images\plomien.svg"><span style="position:absolute; ">' . $_SESSION["days_streak"] . '</span>';
      } else {
        echo "Days streak: 0";
      } 
      ?>
    </li>
  </ul>
</nav>