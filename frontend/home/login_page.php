<?php
  session_start();
  include('backend_func/db.php');
  include('backend_func/login.php');
  $inv_tbl = new loginn();
  $result="";
  $_SESSION["access"] = "";
  if($_SERVER['REQUEST_METHOD']=="POST"){
    if ($_POST['submit']=="LOGIN"){
        $result = $inv_tbl->login($_POST['username'],$_POST['password']);
        if($result!=0){
            $_SESSION["access"] = $result;
            header("Location: lg.php");
            exit();
        }else{
            echo '<script>alert("Invalid Username or Password!")</script>';
        }
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">

    <title>LOGIN</title>
</head>
<body>
    <div class="red-navbar">
        
    </div>

    <div class="squircle">
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
        <form class="login-form" method="POST">
            <div class="input-group">
                <label for="username"></label>
                <input type="text" id="username" name="username" placeholder="username">
            </div>
            <div class="input-group">
                <label for="password"></label>
                <input type="password" id="password" name="password" placeholder="password">
            </div>
            <input class="login-button" type="submit" name="submit" value="LOGIN">
        </form>
    </div>
</body>
</html>
