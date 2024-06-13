<?php



session_start();



// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION["logged_in"])) {
    header("Location: login.php");
    die();
}

include 'database.php'; // Dodaj połączenie z bazą danych

if (isset($_POST["change"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $passwordConf = $_POST["passwordConf"];
    $avatar = isset($_POST["avatar"]) ? $_POST["avatar"] : null;

    $userId = $_SESSION["userid"];
    $errors = array();

    // Sprawdzenie i aktualizacja nazwy użytkownika
    if (!empty($username)) {
        $sql = "SELECT * FROM users WHERE username = ? AND id != ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $username, $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Username already exists");
        } else {
            $sql = "UPDATE users SET username = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $username, $userId);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>Username updated successfully</div>";
            } else {
                array_push($errors, "Failed to update username");
            }
        }
    }

    // Sprawdzenie i aktualizacja hasła
    if (!empty($password) && !empty($passwordConf)) {
        if ($password !== $passwordConf) {
            array_push($errors, "Passwords do not match");
        } elseif (strlen($password) < 8) {
            array_push($errors, "Password must be at least 8 characters");
        } else {
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "si", $passwordhash, $userId);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>Password updated successfully</div>";
            } else {
                array_push($errors, "Failed to update password");
            }
        }
    }

    // Sprawdzenie i aktualizacja avatara
    if (!empty($avatar)) {
        $sql = "UPDATE users SET avatar = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "si", $avatar, $userId);
            mysqli_stmt_execute($stmt);
            echo "<div class='alert alert-success'>Avatar updated successfully</div>";
        } else {
            array_push($errors, "Failed to update avatar");
        }
    }

    // Wyświetlenie błędów, jeśli istnieją
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    }
}
?>

<?php include 'components/header_app.php'; ?>

<!-- Formularz do edytowania danych użytkownika -->
<form action="user_edit.php" method="post">
    <div class="form-group">
        <label class="font-semibold">Change Username</label>
        <input type="text" name="username" class="p-2 form-control" placeholder="<?php echo htmlspecialchars($_SESSION["user"]); ?>">
    </div>

    <div class="form-group">
        <label class="font-semibold">Change Password</label>
        <input type="password" name="password" class="form-control p-2" placeholder="Password">
    </div>

    <div class="form-group">
        <input type="password" name="passwordConf" class="form-control p-2" placeholder="Confirm Password">
    </div>

    <div class="form-group">
        <label class=" font-semibold">Change your avatar:</label><br>
        <div class="flex gap-2 flex-row"><label>
            <input type="radio" name="avatar" value="avatar_1.png">
            <img src="images/uploaded_avatars/avatar_1.png" alt="Avatar 1" width="50">
        </label>
        <label>
            <input type="radio" name="avatar" value="avatar_2.png">
            <img src="images/uploaded_avatars/avatar_2.png" alt="Avatar 2" width="50">
        </label>
        <label>
            <input type="radio" name="avatar" value="avatar_3.png">
            <img src="images/uploaded_avatars/avatar_3.png" alt="Avatar 3" width="50">
        </label>
        <label>
            <input type="radio" name="avatar" value="avatar_4.png">
            <img src="images/uploaded_avatars/avatar_4.png" alt="Avatar 4" width="50">
        </label>
        <label>
            <input type="radio" name="avatar" value="avatar_5.png">
            <img src="images/uploaded_avatars/avatar_5.png" alt="Avatar 5" width="50">
        </label></div>
        
    </div>

    <div class="form-button w-full bg-purple-500 p-2 font-semibold text-white">
        <button type="submit" name="change" class="change_btn font-semibold">Change</button>
    </div>
</form>

<?php include 'components/footer_app.php'; ?>
