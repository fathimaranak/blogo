<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
   header('location:home.php');
   exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$fetch_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$fetch_profile->execute([$user_id]);
$profile_data = $fetch_profile->fetch(PDO::FETCH_ASSOC);

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

   if(!empty($name)){
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->execute([$name, $user_id]);
   }

   if(!empty($email)){
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND id != ?");
      $select_email->execute([$email, $user_id]);
      if($select_email->rowCount() > 0){
         $message = 'Email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->execute([$email, $user_id]);
      }
   }

   // Password update
   if (!empty($_POST['old_pass']) && !empty($_POST['new_pass']) && !empty($_POST['confirm_pass'])) {
      $old_pass = $_POST['old_pass'];
      $new_pass = $_POST['new_pass'];
      $confirm_pass = $_POST['confirm_pass'];

      if (!password_verify($old_pass, $profile_data['password'])) {
         $message = 'Old password is incorrect!';
      } elseif ($new_pass !== $confirm_pass) {
         $message = 'Confirm password does not match!';
      } else {
         $hashed_pass = password_hash($new_pass, PASSWORD_BCRYPT);
         $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
         $update_pass->execute([$hashed_pass, $user_id]);
         $message = 'Password updated successfully!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .password-container {
         position: relative;
         width: 100%;
      }

      .password-container .box {
         width: 100%;
         padding-right: 3.5rem;
      }

      .password-container i {
         position: absolute;
         right: 1.2rem;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: #777;
      }

      .password-container i:hover {
         color: #000;
      }
   </style>
</head>
<body>
   
<!-- Header -->
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Update Profile</h3>

      <?php if(isset($message)) { echo "<p style='color:red;'>$message</p>"; } ?>

      <input type="text" name="name" placeholder="<?= htmlspecialchars($profile_data['name']); ?>" class="box" maxlength="50">
      <input type="email" name="email" placeholder="<?= htmlspecialchars($profile_data['email']); ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Password Fields with Eye Icon -->
      <div class="password-container">
         <input type="password" name="old_pass" id="old_pass" placeholder="Enter old password" class="box" maxlength="50">
         <i class="fas fa-eye" onclick="togglePassword('old_pass', this)"></i>
      </div>

      <div class="password-container">
         <input type="password" name="new_pass" id="new_pass" placeholder="Enter new password" class="box" maxlength="50">
         <i class="fas fa-eye" onclick="togglePassword('new_pass', this)"></i>
      </div>

      <div class="password-container">
         <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Confirm new password" class="box" maxlength="50">
         <i class="fas fa-eye" onclick="togglePassword('confirm_pass', this)"></i>
      </div>

      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>

</section>

<!-- Footer -->
<?php include 'components/footer.php'; ?>

<!-- JavaScript for toggling password visibility -->
<script>
   function togglePassword(fieldId, icon) {
      let field = document.getElementById(fieldId);
      if (field.type === "password") {
         field.type = "text";
         icon.classList.remove("fa-eye");
         icon.classList.add("fa-eye-slash");
      } else {
         field.type = "password";
         icon.classList.remove("fa-eye-slash");
         icon.classList.add("fa-eye");
      }
   }
</script>

</body>
</html>
