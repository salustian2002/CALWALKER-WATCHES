<?php
session_start();

if(!isset($_SESSION['admin'])) {
    header("location: login.php");
}

require("conn.php");


if(isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $rpass = $_POST['rpassword'];
    $utype = "employee";

    $uname = trim($uname);
    $email = trim($email);
    
    $uname = addslashes($uname);
    $email =addslashes($email);
    $pass = addslashes($pass);
    $rpass = addslashes($rpass);

    $_SESSION['uname'] = $uname;
    $_SESSION['pass'] = $pass;
    $_SESSION['email'] = $email;
    $_SESSION['rpass'] = $rpass;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid email";
        exit;
    }


    $sql = mysqli_query($conn, "SELECT * FROM users 
    WHERE username = '$uname'");

    if(mysqli_num_rows($sql) > 0){
        $_SESSION['msg'] = "Username Already Exists";
    } else {
        if($pass !== $rpass) {
            $_SESSION['msg'] = "Password Doesn't Match..";
        } else {
            $pass = sha1($pass);
            
            $sql2 = "INSERT INTO users (username, email, password, usertype)
             VALUES('$uname','$email','$pass','$utype')";

            if(mysqli_query($conn, $sql2)) {
                unset($_SESSION['uname']);
                unset($_SESSION['email']);
                unset($_SESSION['pass']);
                unset($_SESSION['rpass']);
                $_SESSION['msg'] = "Account has been created successfully";
            } else {
                echo mysqli_error($conn);
            }
        }
    }


}

$users = mysqli_query($conn, "SELECT * FROM users");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - FILM</title>
    <link rel="stylesheet" href="style.css">
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
        </div>
        <?php endif; ?>
                <form action="" method="POST" style="margin-left: 320px;">
                    <h3>REGISTER EMPLOYEE...</h3>
                    <label> Username: </label>
                    <input type="text" name="username" value="<?=$_SESSION['uname'] ?? ''?>" required>
                    
                    <label>Email: </label>
                    <input type="email" name="email" value="<?=$_SESSION['email'] ?? ''?>" required>
                   
                    <label> Password:  </label>
                    <input type="password" name="password" value="<?=$_SESSION['pass'] ?? ''?>" required>
               
                    <label>Repeat Password: </label>
                    <input type="password" name="rpassword" value="<?=$_SESSION['rpass'] ?? ''?>" required>
              
                    <input type="submit" name="submit" value="Register">
                    <p style="text-align:center; color:red">
                    <?=$_SESSION['msg']??''; unset($_SESSION['msg'])?></p>
                </form>

                </div>
</body>
</html>