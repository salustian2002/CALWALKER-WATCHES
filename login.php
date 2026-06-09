<?php
session_start();

if(isset($_SESSION['username'])) {
    header("location: home.php");
}

if(isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $pass = $_POST['password'];

    $uname = trim($uname);

    $uname = addslashes($uname);
    $pass = addslashes($pass);

    $_SESSION['uname'] = $uname;
    $_SESSION['pass'] = $pass;

    require("conn.php");

    $pass = sha1($pass);

    $sql = mysqli_query($conn, "SELECT * FROM users 
    WHERE username = '$uname' AND password = '$pass'");

    if(mysqli_num_rows($sql) > 0) {
        $row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
    unset($_SESSION['uname']);
        unset($_SESSION['pass']);
        
        if($row['usertype'] === 'admin') {
            $_SESSION['uid'] = uniqid('admin');
            $_SESSION['username'] = $row['username'];
            $_SESSION['admin'] = $row['username'];
            $_SESSION['utype'] = $row['usertype'];
            header("location: home.php");
        } elseif($row['usertype'] === 'employee') {
     
           $_SESSION['uid'] = uniqid('emp');
            $_SESSION['username'] = $row['username'];
            $_SESSION['employee'] = $row['username'];
            $_SESSION['utype'] = $row['usertype'];
            header("location: home.php");
            
        } elseif($row['usertype'] === 'user') {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user'] = $row['username'];
            $_SESSION['utype'] = $row['usertype'];
            header("location: home.php");
        } else {
            $_SESSION['msg'] = "Unknown user account";
        }

    } else {
        $_SESSION['msg'] = "Incorrect username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    </head>
    <body>
       <form action="" method="POST">
            <h2>User Login Form</h2>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?=$_SESSION['uname'] ?? ''?>" required>
        
            <label for="password">Password:</label>
            <input type="password"  name="password" value="<?=$_SESSION['pass'] ?? ''?>" required>
        
            <input type="submit" name="submit" value="Login">

            <p>Dont have an account? <a href="signup.php">Register here</a></p>
            <p style="text-align:center; color:red"><?=$_SESSION['msg']??''; 
            unset($_SESSION['msg'])?></p><br>
           
        </form>
    </div>
</body>
</html>