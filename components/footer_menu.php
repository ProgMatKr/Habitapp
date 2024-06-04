<?php require_once "database.php"; ?>





<div id="account-panel" class="account-panel">
    <a class="closeMenu" id="closeMenu">Close</a>
    <div class="inside">
        <img src="\images\user.svg" alt="User" class="user-image" id="avatar">
        <p><?php echo htmlspecialchars($_SESSION["user"]); ?></p>
        <form id="settingsForm">
            <div class="form-group">
                <label for="avatarUpload">Change Avatar:</label>
                <input type="file" id="avatarUpload" name="avatarUpload" accept="image/*">
            </div>
            <div class="form-group">
                <label for="colorPicker">Change Application Color:</label>
                <input type="color" id="colorPicker" name="colorPicker">
            </div>
            <div class="form-group">
                <label for="username">Change Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['user']); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
    <a href="logout.php" class="btn btn-warning">Logout</a>
</div>