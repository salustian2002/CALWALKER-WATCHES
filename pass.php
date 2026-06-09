<?php
require('auth.php');

require("conn.php");

$errors = array('pass'=>'','npass'=>'','rpass'=>'');

if(isset($_POST['submit'])) {
    $pass = $_POST['password'];
    $npass = $_POST['npassword'];
    $rpass = $_POST['rpassword'];

    $_SESSION['pass'] = $pass;
    $_SESSION['rpass'] = $rpass;
    $_SESSION['npass'] = $npass;

    if(empty($pass)) {
        $errors['pass'] = "Current password is required";
    }

    if(empty($npass)) {
        $errors['npass'] = "New password is required";
    }

    if(empty($rpass)) {
        $errors['rpass'] = "Repeat password is required";
    } elseif($npass !== $rpass) {
        $errors['rpass'] = "Passwords doesnt match";
    }

    if(!array_filter($errors)) {
        $sql = mysqli_query($conn, "SELECT password FROM users 
        WHERE username = '{$_SESSION['username']}'");

        $row = mysqli_fetch_array($sql);

        if($row['password'] === sha1($pass)) {
            $npass = sha1($npass);
            $update = mysqli_query($conn, "UPDATE users SET password = 
            '$npass' WHERE username = '{$_SESSION['username']}'");

            if($update) {
                unset($_SESSION['pass']);
                unset($_SESSION['npass']);
                unset($_SESSION['rpass']);
                $_SESSION['msg'] = "Password has been updated successfully";
            } else {
                $_SESSION['msg'] = mysqli_error($conn);
            }
        } else {
            $errors['pass'] = "Current password is incorrect";
        }
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Film</title>
    <link rel="stylesheet" href="style.css">
    <style>

    </style>
</head>
<body>
<div id="container">
    <div id="sidebar">
      <h2>FILM MANAGEMENT SYSTEM</h2>
      <ul>
         <?php if(isset($_SESSION['admin'])): ?>
        <span>Logged In as: <b><?=$_SESSION['utype']?>
        <li><a href="home.php" class="a">Home</a></li>
        <li> <a href="add.php">Add Movie</a></li>
        <li> <a href="register.php">Register</a></li>
        <li> <a href="pass.php">Change Password</a></li>
        <li><a href="logout.php">Logout</a></li> 
        <!-- Add more menu items as needed -->
        </ul>

        <?php elseif(isset($_SESSION['employee'])): ?>
        <span>Logged In as: <b><?=$_SESSION['utype']?>
        <li><a href="home.php" class="a">Home</a></li>
        <li> <a href="add.php">Add Movie</a></li>
        <li> <a href="pass.php">Change Password</a></li>
        <li><a href="logout.php">Logout</a></li>

        <?php elseif(isset($_SESSION['user'])): ?>
        <span>Logged In as: <b><?=$_SESSION['utype']?></b></span>
        <li><a href="home.php" class="a">Home</a></li>
        <li> <a href="pass.php">Change Password</a></li>
        <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </div>
                <form class="updatep" action="" method="post" style="margin-left: 320px;">
                    <h3 align="center">CHANGE PASSWORD</h3>
                    <label>
                        Current Password
                        <input type="password" name="password" value="<?=$_SESSION['pass'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['pass'] ?? ''?></p>
                    </label>
                    <label>
                        New Password
                        <input type="password" name="npassword" value="<?=$_SESSION['npass'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['npass'] ?? ''?></p>
                    </label>
                    <label>
                        Repeat Password
                        <input type="password" name="rpassword" value="<?=$_SESSION['rpass'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['rpass'] ?? ''?></p>
                    </label>
                    <input type="submit" name="submit" value="Update Password">
                    <p style="text-align:center; color:red"><?=$_SESSION['msg']??''; unset($_SESSION['msg'])?></p>
                </form>
          
    </div>
</body>
</html>