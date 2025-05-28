<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">

   <style>
      .password-container {
         position: relative;
         width: 100%;
      }

      .password-container input {
         width: 100%;
         padding-right: 40px;
      }

      .password-container .toggle-password {
         position: absolute;
         right: 10px;
         top: 50%;
         transform: translateY(-50%);
         cursor: pointer;
         color: #777;
      }
   </style>
</head>
<body>
   
<!-- Header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- Header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <div class="password-container">
         <input type="password" name="pass" id="password" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <i class="fa fa-eye toggle-password" onclick="togglePassword()"></i>
      </div>
      
      <input type="submit" value="Login Now" name="submit" class="btn">
      <p>Don't have an account? <a href="register.php">Register Now</a></p>
   </form>

</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file link -->
<script src="js/script.js"></script>

<script>
   function togglePassword() {
      const passwordInput = document.getElementById("password");
      const toggleIcon = document.querySelector(".toggle-password");

      if (passwordInput.type === "password") {
         passwordInput.type = "text";
         toggleIcon.classList.remove("fa-eye");
         toggleIcon.classList.add("fa-eye-slash");
      } else {
         passwordInput.type = "password";
         toggleIcon.classList.remove("fa-eye-slash");
         toggleIcon.classList.add("fa-eye");
      }
   }
</script>

</body>
</html>
