<?php
session_start();
if(!isset($_SESSION['admin'])) {
    header("location: login.php");
}
require("conn.php");

if(isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $email = $_POST['email'];
    $utype = $_POST['utype'];

    $uname = trim($uname);
    $email = trim($email);
    
    $uname = addslashes($uname);
    $email =addslashes($email);

    $_SESSION['uname'] = $uname;
    $_SESSION['email'] = $email;
    $_SESSION['utype'] = $utype;

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['msg'] = "Invalid email";
        exit;
    }

    switch($_SESSION['utype']){
    case 'Admin':
        $administrator = "Selected";
        $employee = "";   
        break; 
    case 'Employee':
        $administrator = "";
        $employee = "Selected";   
        break;  
    default:
        $administrator = "";
        $employee = "";  
        break; 
}

    $sql2 = "UPDATE users SET username = '$uname', email = '$email', usertype = '$utype' WHERE username = '{$_GET['uname']}'";

    if(mysqli_query($conn, $sql2)) {
        unset($_SESSION['uname']);
        unset($_SESSION['email']);
        $_SESSION['delete'] = $uname."'s Account has been updated successfully";
        header("location: register.php");
    } else {
        echo mysqli_error($conn);
    }
}

$user = mysqli_query($conn, "SELECT * FROM users WHERE username = '{$_GET['uname']}'");
$row = mysqli_fetch_assoc($user);

    switch($row['usertype']){
    case 'admin':
        $administrator = "Selected";
        $employee = "";   
        break; 
    case 'employee':
        $administrator = "";
        $employee = "Selected";   
        break;  
    default:
        $administrator = "";
        $employee = "";  
        break; 
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit - Film</title>
    <link rel="stylesheet" href="styles.css">
    <style>

    </style>
</head>
<body>
    <div class="home">
    <header>
            <h2>FILM</h2>
            <?php if(isset($_SESSION['admin'])): ?>
                <nav>
                    <span>Logged In as: <b><?=$_SESSION['utype']?></b></span>
                    <a href="home.php">Home</a>
                    <a href="add.php">Add Movie</a>
                    <a href="register.php"class="a">Register</a>
                    <a href="pass.php">Change Password</a>
                    <a href="logout.php">Logout</a>
                </nav>
            <?php elseif(isset($_SESSION['employee'])): ?>
                <!-- <nav>
                    <span>Logged In as: <b><?=$_SESSION['utype']?></b></span>
                    <a href="home.php">Home</a>
                    <a href="add.php">Add Movie</a>
                    <a href="pass.php">Change Password</a>
                    <a href="logout.php">Logout</a>
                </nav> -->
            <?php elseif(isset($_SESSION['user'])): ?>
                <!-- <nav>
                    <span>Logged In as: <b><?=$_SESSION['utype']?></b></span>
                    <a href="home.php">Home</a>
                    <a href="pass.php">Change Password</a>
                    <a href="logout.php">Logout</a>
                </nav> -->
            <?php endif; ?>
        </header>
        <main>
        <div class="formr">
                <form class="flog" action="" method="post">
                    <h3 align="center">EDIT FORM</h3>
                    <label>
                        Username
                        <input type="text" name="username" value="<?=$row['username'] ?? ''?>" required>
                    </label>
                    <label>
                        Email
                        <input type="text" name="email" value="<?=$row['email'] ?? ''?>">
                    </label>
                    <label>
                        usertype
                        <select name="utype">
                            <option selected disabled>Select usertype..</option>
                            <option value="employee" <?=$employee ?? ''?>>Employee</option>
                            <option value="admin" <?=$administrator ?? ''?>>Admin</option>
                        </select>
                    </label>
                    <input type="submit" name="submit" value="Update User">
                    <p style="text-align:center; color:red"><?=$_SESSION['msg']??''; unset($_SESSION['msg'])?></p>
                </form>

                <!--  -->
            </div>
        </main>
    </div>
</body>
</html>