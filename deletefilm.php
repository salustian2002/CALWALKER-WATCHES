<?php
session_start();

if(isset($_SESSION['uid'])) {

require("conn.php");


$sql = mysqli_query($conn, "DELETE FROM film WHERE filmID = '{$_GET['id']}'");

if($sql) {
    $_SESSION['delete'] = "Film has been deleted successfully";
    unlink("photos/".$row[6]);
    header("location: home.php");
}

} else {
    header("location: login.php");
}