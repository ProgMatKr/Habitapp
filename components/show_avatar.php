<?php 


$user_id = $_SESSION["userid"];
$sql = "SELECT avatar FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

echo "<img src='images/uploaded_avatars/" . $user["avatar"] . "' alt='User Avatar'>";

?>