<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

// Ensure database connection exists
include '../components/connect.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

// Fetch admin profile if logged in
$admin_name = "Admin";
if ($admin_id) {
    $select_profile = $conn->prepare("SELECT name FROM `admin` WHERE id = ?");
    $select_profile->execute([$admin_id]);
    $admin_name = $select_profile->fetchColumn() ?: "Admin";
}
?>

<header class="header">
    <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

    <div class="profile">
        <?php if ($admin_id): // Show only if logged in ?>
            <p><?= htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
        <?php else: ?>
            <p>Guest</p>
        <?php endif; ?>
    </div>

    <nav class="navbar">
        <a href="dashboard.php"><i class="fas fa-home"></i> <span>Home</span></a>
        <a href="add_posts.php"><i class="fas fa-pen"></i> <span>Add Posts</span></a>
        <a href="view_posts.php"><i class="fas fa-eye"></i> <span>View Posts</span></a>
        <a href="admin_accounts.php"><i class="fas fa-user"></i> <span>Accounts</span></a>

        <?php if ($admin_id): // Show logout only if logged in ?>
            <a href="../components/admin_logout.php" style="color:var(--red);" onclick="return confirm('Logout from the website?');">
                <i class="fas fa-right-from-bracket"></i><span>Logout</span>
            </a>
        <?php endif; ?>
    </nav>

    <div class="flex-btn">
        <?php if (!$admin_id): // Show login/register only if NOT logged in ?>
            <a href="admin_login.php" class="option-btn">Login</a>
            <a href="register_admin.php" class="option-btn">Register</a>
        <?php endif; ?>
    </div>
</header>

<div id="menu-btn" class="fas fa-bars"></div>
