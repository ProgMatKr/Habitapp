<?php 

$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "habitapp";



$conn = mysqli_connect("$host", "$dbUsername", "$dbPassword", "$dbName");
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}
?> 