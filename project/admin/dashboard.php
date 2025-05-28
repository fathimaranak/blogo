<?php
include '../components/connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // header('location:admin_login.php'); // Uncomment this for login redirect
    $admin_id = null;
} else {
    $admin_id = $_SESSION['admin_id'];
}

// Fetch admin profile
$admin_name = "Admin"; // Default fallback name
if ($admin_id) {
    $select_profile = $conn->prepare("SELECT name FROM `admin` WHERE id = ?");
    $select_profile->execute([$admin_id]);
    $admin_name = $select_profile->fetchColumn() ?: "Admin";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Admin Dashboard Section -->
<section class="dashboard">
    <h1 class="heading">Dashboard</h1>

    <div class="box-container">

        <div class="box">
            <h3>Welcome!</h3>
            <p><?= htmlspecialchars($admin_name, ENT_QUOTES, 'UTF-8'); ?></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
        </div>

        <div class="box">
            <?php
            $numbers_of_posts = 0;
            if ($admin_id) {
                $select_posts = $conn->prepare("SELECT COUNT(*) FROM `posts` WHERE admin_id = ?");
                $select_posts->execute([$admin_id]);
                $numbers_of_posts = $select_posts->fetchColumn();
            }
            ?>
            <h3><?= $numbers_of_posts; ?></h3>
            <p>Posts Added</p>
            <a href="add_posts.php" class="btn">Add New Post</a>
        </div>

        <div class="box">
            <?php
            $numbers_of_active_posts = 0;
            if ($admin_id) {
                $select_active_posts = $conn->prepare("SELECT COUNT(*) FROM `posts` WHERE admin_id = ? AND status = ?");
                $select_active_posts->execute([$admin_id, 'active']);
                $numbers_of_active_posts = $select_active_posts->fetchColumn();
            }
            ?>
            <h3><?= $numbers_of_active_posts; ?></h3>
            <p>Active Posts</p>
            <a href="view_posts.php" class="btn">See Posts</a>
        </div>

        <div class="box">
            <?php
            $numbers_of_deactive_posts = 0;
            if ($admin_id) {
                $select_deactive_posts = $conn->prepare("SELECT COUNT(*) FROM `posts` WHERE admin_id = ? AND status = ?");
                $select_deactive_posts->execute([$admin_id, 'deactive']);
                $numbers_of_deactive_posts = $select_deactive_posts->fetchColumn();
            }
            ?>
            <h3><?= $numbers_of_deactive_posts; ?></h3>
            <p>Deactive Posts</p>
            <a href="view_posts.php" class="btn">See Posts</a>
        </div>

        <div class="box">
            <?php
            $select_users = $conn->prepare("SELECT COUNT(*) FROM `users`");
            $select_users->execute();
            $numbers_of_users = $select_users->fetchColumn();
            ?>
            <h3><?= $numbers_of_users; ?></h3>
            <p>Users Accounts</p>
            <a href="users_accounts.php" class="btn">See Users</a>
        </div>

        <div class="box">
            <?php
            $select_admins = $conn->prepare("SELECT COUNT(*) FROM `admin`");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->fetchColumn();
            ?>
            <h3><?= $numbers_of_admins; ?></h3>
            <p>Admin Accounts</p>
            <a href="admin_accounts.php" class="btn">See Admins</a>
        </div>

        <div class="box">
            <?php
            $numbers_of_comments = 0;
            if ($admin_id) {
                $select_comments = $conn->prepare("SELECT COUNT(*) FROM `comments` WHERE admin_id = ?");
                $select_comments->execute([$admin_id]);
                $numbers_of_comments = $select_comments->fetchColumn();
            }
            ?>
            <h3><?= $numbers_of_comments; ?></h3>
            <p>Comments Added</p>
            <a href="comments.php" class="btn">See Comments</a>
        </div>

        <div class="box">
            <?php
            $numbers_of_likes = 0;
            if ($admin_id) {
                $select_likes = $conn->prepare("SELECT COUNT(*) FROM `likes` WHERE admin_id = ?");
                $select_likes->execute([$admin_id]);
                $numbers_of_likes = $select_likes->fetchColumn();
            }
            ?>
            <h3><?= $numbers_of_likes; ?></h3>
            <p>Total Likes</p>
            <a href="view_posts.php" class="btn">See Posts</a>
        </div>

    </div>

</section>

<!-- Custom JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
