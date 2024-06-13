<?php require_once "database.php"; ?>


















<div id="account-panel" class="account-panel">
    <a class="closeMenu" id="closeMenu">Close</a>
    <div class="inside">
    <?php include 'components/show_avatar.php'; ?>
    <p><?php echo htmlspecialchars($_SESSION["user"]); ?></p>
    </div>
    <a href="user_edit.php" class="btn btn-warning bg-purple-500 text-white font-semibold mb-2">Edit profile</a>
    <a href="logout.php" class="btn btn-warning bg-red-500 text-white font-semibold">Logout</a>
</div>