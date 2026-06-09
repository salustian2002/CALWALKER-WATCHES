<?php
session_start();

if(isset($_SESSION['uid'])) {

$errors = array();

require('conn.php');

$res = mysqli_query($conn, "SELECT * FROM film 
WHERE filmID = '{$_GET['id']}'");
$row = mysqli_fetch_array($res, MYSQLI_BOTH);

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

    if(empty($title)) {
        $errors['title'] = "Title is required";
    } elseif(!preg_match("/^[a-zA-Z' ]+$/", $title)) {
        $errors['title'] = "Title must contain only letters";
    }

    if(empty($producer)) {
        $errors['producer'] = "Producer is required";
    } elseif(!preg_match("/^[a-zA-Z' ]+$/", $producer)) {
        $errors['producer'] = "Producer must contain only letters";
    }

    if(empty($theme)) {
        $errors['theme'] = "Theme is required";
    }

    if(empty($year)) {
        $errors['year'] = "Year is required";
    } elseif(!preg_match('/^[1-2][0-9]{3}$/', $year)) {
        $errors['year'] = "Year must be a valid year eg. 1990";
    }

    if(empty($_FILES['photo']['name'])) {
        $errors['photo'] = "Film cover is required";
    }

    if(empty($price)) {
        $errors['price'] = "Price is required";
    }

    if(!array_filter($errors)) {
        if(isset($_FILES['photo']['name'])) {
            $img_name = $_FILES['photo']['name'];
            $tmp_name = $_FILES['photo']['tmp_name'];
            $extensions = array('png','jpg','jpeg','webp');

            $img_exp = explode('.', $img_name);
            $img_ext = strtolower(end($img_exp));

            $img_name = time().$img_name;
            $directory = "photos/".$img_name;
            if(in_array($img_ext, $extensions)) {
                move_uploaded_file($tmp_name, $directory);

                $sql = "UPDATE film SET title = '$title', price = '$price', producer = '$producer', year = '$year', theme = '$theme', image = '$img_name' WHERE filmID = '{$_GET['id']}'";
                $success = mysqli_query($conn, $sql);

                if($success) {
                    unlink("photos/".$row[6]);
                    $_SESSION['msg'] = "A Film has been updated successfully";
                } else {
                    $_SESSION['msg'] = mysqli_error($conn);
                }
               
            } else {
                $errors['photo'] = "Image extension is not valid";
            }
        }
    }

}

$res = mysqli_query($conn, "SELECT * FROM film WHERE filmID = '{$_GET['id']}'");
$row = mysqli_fetch_array($res, MYSQLI_BOTH);

switch($row['theme']) {
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Film - FILM</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="home">
    <header>
            <h2>FILM</h2>
            <?php if(isset($_SESSION['admin'])): ?>
                <nav>
                    <span>Logged In as: <b><?=$_SESSION['utype']?></b></span>
                    <a href="home.php">Home</a>
                    <a href="add.php" class="a">Add Movie</a>
                    <a href="register.php">Register</a>
                    <a href="pass.php">Change Password</a>
                    <a href="logout.php">Logout</a>
                </nav>
            <?php elseif(isset($_SESSION['employee'])): ?>
                <nav>
                    <span>Logged In as: <b><?=$_SESSION['utype']?></b></span>
                    <a href="home.php">Home</a>
                    <a href="add.php" class="a">Add Movie</a>
                    <a href="pass.php">Change Password</a>
                    <a href="logout.php">Logout</a>
                </nav>
            <?php endif; ?>
        </header>
        <main>
        <div class="form fnw">
                <form class="updatep" action="" method="post" autocomplete="on" enctype="multipart/form-data">
                    <h3 align="center">EDIT FILM</h3>
                    <label>
                        Title
                        <input type="text" name="title" value="<?=$row['title'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['title'] ?? ''?></p>
                    </label>
                    <label>
                        Producer
                        <input type="text" name="producer" value="<?=$row['producer'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['producer'] ?? ''?></p>
                    </label>
                    <label>
                        Price
                        <input type="number" name="price" value="<?=$row['price'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['price'] ?? ''?></p>
                    </label>
                    <label>
                        Year of Production
                        <input type="text" name="year" value="<?=$row['year'] ?? ''?>">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['year'] ?? ''?></p>
                    </label>
                    <label>
                        Film Cover Photo
                        <input type="file" name="photo">
                        <p style="color:red; margin-bottom: 10px"><?=$errors['photo'] ?? ''?></p>
                        <img src="photos/<?=$row['image']?>" alt="">
                    </label>
                    <label>
                        Theme
                        <select name="theme">
                            <option selected disabled>Select theme..</option>
                            <option value="Comedy" <?=$comedy ?? ''?>>Comedy</option>
                            <option value="Drama" <?=$drama ?? ''?>>Drama</option>
                            <option value="Adventure" <?=$adventure ?? ''?>>Adventure</option>
                            <option value="Advocacy" <?=$advocacy ?? ''?>>Advocacy</option>
                            <option value="Education" <?=$education ?? ''?>>Education</option>
                        </select>
                        <p style="color:red; margin-bottom: 10px"><?=$errors['theme'] ?? ''?></p>
                    </label>
                    <input type="submit" name="submit" value="Upload Film">
                    <p style="text-align:center; color:red"><?=$_SESSION['msg']??''; unset($_SESSION['msg'])?></p>
                </form>
            </div>
        </main>
    </div>
</body>
</html>

<?php 
} else {
header("location: login.php");
}