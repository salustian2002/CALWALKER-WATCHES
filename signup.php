<?php
session_start();

if(isset($_SESSION['username'])) {
    header("location: home.php");
}

if(isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $rpass = $_POST['rpassword'];
    $utype = "user";

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

    require("conn.php");

    $sql = mysqli_query($conn, "SELECT * FROM users 
    WHERE username = '$uname'");

    if(mysqli_num_rows($sql) > 0){
        $_SESSION['msg'] = "Username already exists";
    } else {
        if($pass !== $rpass) {
            $_SESSION['msg'] = "Password doesnt match..";
        } else {
            $pass = sha1($pass);
            
            $sql2 = "INSERT INTO users (username, email, password, usertype)
             VALUES('$uname','$email','$pass','$utype')";

            if(mysqli_query($conn, $sql2)) {
                unset($_SESSION['uname']);
                unset($_SESSION['email']);
                unset($_SESSION['pass']);
                unset($_SESSION['rpass']);
                $_SESSION['msg'] = "Your account has been created";
            } else {
                echo mysqli_error($conn);
            }
        }
    }


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form">
        <form class="flog" action="" method="post">
            <h3>USER REGISTRATION...</h3>
            <label>
                Username
                <input type="text" name="username" 
                value="<?=$_SESSION['uname'] ?? ''?>">
            </label>
            <label>
                Email
                <input type="email" name="email" 
                value="<?=$_SESSION['email'] ?? ''?>">
            </label>
            <label>
                Password
                <input type="password" name="password" 
                value="<?=$_SESSION['pass'] ?? ''?>">
            </label>
            <label>
                Repeat Password
                <input type="password" name="rpassword" 
                value="<?=$_SESSION['rpass'] ?? ''?>">
            </label>
            <input type="submit" name="submit" value="Register">
            <p style="text-align:center; color:red">
            <?=$_SESSION['msg']??''; unset($_SESSION['msg'])?></p>
            <p align="center">Already have an account?
                 login <a style="color: crimson;
                 " href="login.php">here</a></p>
        </form>
    </div>
</body>
</html>