<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Choose Registration</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <style>
      :root{
         --main-color:#000080;
         --secondary-color:#4b0082;
         --border-radius: 10px;
         --input-bg: #f5f5f5;
         --input-border: #ccc;
         --input-padding: 1rem;
         --text-color: #333;
         --hover-color: #000;
      }

      *{
         font-family: "Rubik", sans-serif;
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         text-decoration: none;
      }

      body{
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         background-color: var(--input-bg);
      }

      .register-container{
         background: white;
         padding: 2rem;
         border-radius: var(--border-radius);
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
         text-align: center;
         width: 400px;
         border: 2px solid var(--text-color); /* Added border */
      }

      .register-container h3{
         font-size: 2rem;
         margin-bottom: 1.5rem;
         color: var(--text-color);
      }

      .btn{
         width: 100%;
         padding: var(--input-padding);
         background: var(--main-color);
         color: white;
         font-size: 1.2rem;
         border: none;
         border-radius: var(--border-radius);
         cursor: pointer;
         margin-top: 1rem;
      }

      .btn:hover{
         background: var(--hover-color);
      }

      .login-link{
         display: block;
         margin-top: 1rem;
         color: var(--secondary-color);
      }

      .login-link:hover{
         color: var(--main-color);
      }
   </style>
</head>
<body>
   <div class="register-container">
      <h3>Register as</h3>
      <button class="btn" onclick="window.location.href='register.php'">User</button>
      <button class="btn" onclick="window.location.href='admin/register_admin.php'">Admin</button>
   </div>
</body>
</html>
