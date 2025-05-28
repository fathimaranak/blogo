<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   if(!empty($name)){
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);
      if($select_name->rowCount() > 0){
         $message[] = 'username already taken!';
      }else{
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
      }
   }

   $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);
   $prev_pass = $fetch_prev_pass['password'];
   $old_pass = sha1($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = sha1($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = sha1($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   if($old_pass != $empty_pass){
      if($old_pass != $prev_pass){
         $message[] = 'old password not matched!';
      }elseif($new_pass != $confirm_pass){
         $message[] = 'confirm password not matched!';
      }else{
         if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'password updated successfully!';
         }else{
            $message[] = 'please enter a new password!';
         }
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
   <title>Profile Update</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
      .password-container {
         position: relative;
      }
      .password-container input {
         width: 100%;
         padding-right: 40px;
      }
      .password-container i {
         position: absolute;
         right: 10px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- Admin profile update section starts -->

<section class="form-container">
   <form action="" method="POST">
      <h3>Update Profile</h3>
      <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" placeholder="<?= $fetch_profile['name']; ?>">
      
      <div class="password-container">
         <input type="password" id="old_pass" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fas fa-eye" onclick="togglePassword('old_pass', this)"></i>
      </div>
      
      <div class="password-container">
         <input type="password" id="new_pass" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fas fa-eye" onclick="togglePassword('new_pass', this)"></i>
      </div>
      
      <div class="password-container">
         <input type="password" id="confirm_pass" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fas fa-eye" onclick="togglePassword('confirm_pass', this)"></i>
      </div>
      
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>
</section>

<!-- Admin profile update section ends -->

<!-- Custom JS File Link -->
<script src="../js/admin_script.js"></script>
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
