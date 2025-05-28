<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

include 'components/like_post.php';

$get_id = $_GET['post_id'];

// Fetch user profile details
$fetch_profile = ['name' => 'Guest']; // Default value

if (!empty($user_id)) {
    $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_profile->execute([$user_id]);

    if ($select_profile->rowCount() > 0) {
        $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
    }
}

if(isset($_POST['add_comment'])){
   $admin_id = filter_var($_POST['admin_id'], FILTER_SANITIZE_STRING);
   $user_name = filter_var($_POST['user_name'], FILTER_SANITIZE_STRING);
   $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ? AND admin_id = ? AND user_id = ? AND user_name = ? AND comment = ?");
   $verify_comment->execute([$get_id, $admin_id, $user_id, $user_name, $comment]);

   if($verify_comment->rowCount() > 0){
      $message[] = 'Comment already added!';
   } else {
      $insert_comment = $conn->prepare("INSERT INTO `comments`(post_id, admin_id, user_id, user_name, comment) VALUES(?,?,?,?,?)");
      $insert_comment->execute([$get_id, $admin_id, $user_id, $user_name, $comment]);
      $message[] = 'New comment added!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Post</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="posts-container">
   <div class="box-container">
      <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ? AND id = ?");
         $select_posts->execute(['active', $get_id]);

         if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
               $post_id = $fetch_posts['id'];

               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount(); 

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);
      ?>
      <form class="box" method="post">
         <input type="hidden" name="post_id" value="<?= $post_id; ?>">
         <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
         <div class="post-admin">
            <i class="fas fa-user"></i>
            <div>
               <a href="author_posts.php?author=<?= htmlspecialchars($fetch_posts['name']); ?>"><?= htmlspecialchars($fetch_posts['name']); ?></a>
               <div><?= htmlspecialchars($fetch_posts['date']); ?></div>
            </div>
         </div>
         
         <?php if(!empty($fetch_posts['image'])) { ?>
         <img src="uploaded_img/<?= htmlspecialchars($fetch_posts['image']); ?>" class="post-image" alt="">
         <?php } ?>

         <div class="post-title"><?= htmlspecialchars($fetch_posts['title']); ?></div>
         <div class="post-content"><?= nl2br(htmlspecialchars($fetch_posts['content'])); ?></div>
         <div class="icons">
            <div><i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span></div>
            <button type="submit" name="like_post"><i class="fas fa-heart" style="<?= ($confirm_likes->rowCount() > 0) ? 'color:var(--red);' : ''; ?>"></i><span>(<?= $total_post_likes; ?>)</span></button>
         </div>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">No posts found!</p>';
         }
      ?>
   </div>
</section>

<section class="comments-container">
   <p class="comment-title">Add Comment</p>
   <?php if($user_id != '') { ?>
   <form action="" method="post" class="add-comment">
      <input type="hidden" name="admin_id" value="<?= htmlspecialchars($fetch_posts['admin_id']); ?>">
      <input type="hidden" name="user_name" value="<?= htmlspecialchars($fetch_profile['name']); ?>">
      <p class="user"><i class="fas fa-user"></i><a href="update.php"><?= htmlspecialchars($fetch_profile['name']); ?></a></p>
      <textarea name="comment" maxlength="1000" class="comment-box" cols="30" rows="10" placeholder="Write your comment" required></textarea>
      <input type="submit" value="Add Comment" class="inline-btn" name="add_comment">
   </form>
   <?php } else { ?>
   <div class="add-comment">
      <p>Please login to add or edit your comment</p>
      <a href="login.php" class="inline-btn">Login Now</a>
   </div>
   <?php } ?>

   <p class="comment-title">Post Comments</p>
   <div class="user-comments-container">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
         $select_comments->execute([$get_id]);

         if($select_comments->rowCount() > 0){
            while($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="show-comments" style="<?= ($fetch_comments['user_id'] == $user_id) ? 'order:-1;' : ''; ?>">
         <div class="comment-user">
            <i class="fas fa-user"></i>
            <div>
               <span><?= htmlspecialchars($fetch_comments['user_name']); ?></span>
               <div><?= htmlspecialchars($fetch_comments['date']); ?></div>
            </div>
         </div>
         <div class="comment-box"><?= nl2br(htmlspecialchars($fetch_comments['comment'])); ?></div>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No comments added yet!</p>';
         }
      ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
