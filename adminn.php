<?php
require('auth.php');

require('conn.php');

$sql = "SELECT * FROM film";
$results = mysqli_query($conn, $sql);
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

        <table class="content-table">
    <h3 id="mov-header">VIEW ALL AVAILABLE FILMS AND ACTION MOVIES</h3>
        <thead>
         <tr>
            <th>FilmID</th>
            <th>Title</th>
            <th>Producer</th>
            <th>Themes</th>
            <th>Year</th>
            <th>Price</th>
            <th>Downloads</th>
         </tr>
        </thead>
        <tbody>
 <?php
 //establish the connection
 $con = mysqli_connect("localhost", "root", "", "webdb");
 if(!$con){
    echo "Connection failed". mysqli_connect_error($con);
 }

//select database to work with
if(!mysqli_select_db($con,"webdb")){
  $_SESSION['msg']  = mysqli_error($con);
  header("Location: login.php");
  exit;
  } 
  
  $sql = "select * from movies";
  $result = $con->query($sql);
  if(!$result){
     echo"Invalid query entered try again";
  }
  while($row = $result->fetch_assoc()){
    
    echo "
    <tr>
    <td>$row[FilmID]</td>
    <td>$row[Title]</td>
    <td>$row[Producer]</td>
    <td>$row[Theme]</td>
    <td>$row[Year]</td>
    <td>$row[Price]</td>
    <td><a href='www.youtube.com'>click to download</a></td>
</tr>
    ";
   
  }       
?>   
    </div>
</body>
</html>