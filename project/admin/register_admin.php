<?php

include '../components/connect.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   if(strlen($pass) < 6){
      $message[] = 'Password must be at least 6 characters!';
   } else {
      $pass_hash = sha1($pass);
      $cpass_hash = sha1($cpass);

      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_admin->execute([$name]);
      
      if($select_admin->rowCount() > 0){
         $message[] = 'Username already exists!';
      } else {
         if($pass_hash != $cpass_hash){
            $message[] = 'Confirm password does not match!';
         } else {
            $insert_admin = $conn->prepare("INSERT INTO `admin` (name, password) VALUES (?, ?)");
            $insert_admin->execute([$name, $pass_hash]);
            $message[] = 'New admin registered successfully!';
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
   <title>Register</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../css/admin_style.css">

   <style>
      .password-container {
         position: relative;
         width: 100%;
      }
      .password-container input {
         width: 100%;
         padding-right: 40px;
      }
      .password-container .eye-icon {
         position: absolute;
         right: 10px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: #666;
      }
   </style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Register admin section starts -->
<section class="form-container">
   <form action="" method="POST">
      <h3>New Admin</h3>
      <input type="text" name="name" class="box" maxlength="20" required placeholder="Enter your username" oninput="this.value = this.value.replace(/\s/g, '')">

      <div class="password-container">
         <input type="password" name="pass" class="box" id="register-password" maxlength="20" required placeholder="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fa fa-eye eye-icon" id="toggle-register-password"></i>
      </div>

      <div class="password-container">
         <input type="password" name="cpass" class="box" id="confirm-password" maxlength="20" required placeholder="Confirm your password" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fa fa-eye eye-icon" id="toggle-confirm-password"></i>
      </div>

      <input type="submit" value="Register Now" name="submit" class="btn">
      <p>Already registered? <a href="admin_login.php">Login now</a></p>
   </form>
</section>
<!-- Register admin section ends -->

<!-- Custom JS -->
<script>
   document.getElementById('toggle-register-password').addEventListener('click', function() {
      let passwordField = document.getElementById('register-password');
      if (passwordField.type === "password") {
         passwordField.type = "text";
         this.classList.remove('fa-eye');
         this.classList.add('fa-eye-slash');
      } else {
         passwordField.type = "password";
         this.classList.remove('fa-eye-slash');
         this.classList.add('fa-eye');
      }
   });

   document.getElementById('toggle-confirm-password').addEventListener('click', function() {
      let passwordField = document.getElementById('confirm-password');
      if (passwordField.type === "password") {
         passwordField.type = "text";
         this.classList.remove('fa-eye');
         this.classList.add('fa-eye-slash');
      } else {
         passwordField.type = "password";
         this.classList.remove('fa-eye-slash');
         this.classList.add('fa-eye');
      }
   });
</script>

</body>
</html>
<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   
   if($select_admin->rowCount() > 0){
      $message[] = 'username already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm passowrd not matched!';
      }else{
         $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
         $insert_admin->execute([$name, $cpass]);
         $message[] = 'new admin registered!';
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
   <title>register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- register admin section starts  -->

<section class="form-container">

   <form action="" method="POST">
      <h3>register new</h3>
      <input type="text" name="name" maxlength="20" required placeholder="enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" maxlength="20" required placeholder="enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" maxlength="20" required placeholder="confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register admin section ends -->
















<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

</body>
</html>