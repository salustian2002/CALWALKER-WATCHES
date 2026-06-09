<?php
if(!isset($_SESSION['admin'])) {
    header("location: login.php");
}

require("conn.php");


$sql = mysqli_query($conn, "DELETE FROM users WHERE username = '{$_GET['uname']}'");

if($sql) {
    $_SESSION['delete'] = "User has been deleted successfully";
    header("location: register.php");
}