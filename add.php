<?php
session_start();

if(isset($_SESSION['uid'])) {

$errors = array();

if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $producer = $_POST['producer'];
    $theme = $_POST['theme'] ??'';
    $year = $_POST['year'];
    $price = $_POST['price'];

    $title = trim($title);
    $producer = trim($producer);
    $year = trim($year);

    $title = addslashes($title);
    $producer = addslashes($producer);
    $year = addslashes($year);

    $_SESSION['title'] = $title;
    $_SESSION['producer'] = $producer;
    $_SESSION['theme'] = $theme;
    $_SESSION['price'] = $price;
    $_SESSION['year'] = $year;


    switch($_SESSION['theme']) {
        case 'Comedy':
            $comedy = "selected";
            $drama = "";
            $adventure = "";
            $advocacy = "";
            $education = "";
            break;
        case 'Drama':
            $comedy = "";
            $drama = "selected";
            $adventure = "";
            $advocacy = "";
            $education = "";
            break;
        case 'Adventure':
            $comedy = "";
            $drama = "";
            $adventure = "selected";
            $advocacy = "";
            $education = "";
            break;
        case 'Advocacy':
            $comedy = "";
            $drama = "";
            $adventure = "";
            $advocacy = "selected";
            $education = "";
            break;
        case 'Education':
            $comedy = "";
            $drama = "";
            $adventure = "";
            $advocacy = "";
            $education = "selected";
            break;
        default:
            $comedy = "";
            $drama = "";
            $adventure = "";
            $advocacy = "";
            $education = "";
            break;
    }

                require('conn.php');

                $sql = "Insert into movies(FilmID, Title, Producer, Theme, Year, Price)
                Values('', '$title','$producer','$theme','$year',$price)"; 
                $success = mysqli_query($conn, $sql);

                if($success) {
                    unset($_SESSION['title']);
                    unset($_SESSION['producer']);
                    unset($_SESSION['year']);
                    unset($_SESSION['theme']);
                    unset($_SESSION['price']);
                    $_SESSION['msg'] = "A Film has been added successfully";
                } else {
                    $_SESSION['msg'] = mysqli_error($conn);
                }
               
            } else {
                $errors['photo'] = "Image extension is not valid";
            }
        }
    


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

                <form class="updatep" action="" autocomplete="on" method="post" enctype="multipart/form-data" style="margin-left: 320px;">
                    <h3> POPULATE FILM </h3>
                    <label>Title:   </label>
                        <input type="text" name="title" value="<?=$_SESSION['title'] ?? ''?>">
                  
                    <label> Producer: </label>
                        <input type="text" name="producer" value="<?=$_SESSION['producer'] ?? ''?>">

                    <label>Theme: </label>
                        <select name="theme">
                            <option selected disabled>Select theme..</option>
                            <option value="Comedy" <?=$comedy ?? ''?>>Comedy</option>
                            <option value="Drama" <?=$drama ?? ''?>>Drama</option>
                            <option value="Adventure" <?=$adventure ?? ''?>>Adventure</option>
                            <option value="Advocacy" <?=$advocacy ?? ''?>>Advocacy</option>
                            <option value="Education" <?=$education ?? ''?>>Education</option>
                        </select>  
                        
                        
                    <label>Year of Production: </label>
                        <input type="text" name="year" value="<?=$_SESSION['year'] ?? ''?>">
                    
                    <label>Price: </label>
                        <input type="number" name="price" value="<?=$_SESSION['price'] ?? ''?>">

                    
                    <input type="submit" name="submit" value="Add Film">
                    <p style="text-align:center; color:red"><?=$_SESSION['msg']??''; unset($_SESSION['msg'])?></p>
                </form>
    </div>
</body>
</html>

