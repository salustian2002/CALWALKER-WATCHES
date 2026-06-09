<?php if(isset($_SESSION['delete'])): ?>
    <p class="msg"><?=$_SESSION['delete']; unset($_SESSION['delete'])?></p>
<?php endif; ?>
<?php
    if(mysqli_num_rows($results) > 0):
        while($row = mysqli_fetch_array($results, MYSQLI_BOTH)):
?>
<div class="card">
    <img src="photos/<?=$row['image']?>">
    <div class="flex">
        <table>
            <tr>
                <td>Title:</td><td><b><?=$row['title']?></b></td>
            </tr>
            <tr>
                <td>Producer:</td><td><b><?=$row['producer']?></b></td>
            </tr>
            <tr>
                <td>Theme:</td><td><b><?=$row['theme']?></b></td>
            </tr>
            <tr>
                <td>Year:</td><td><b><?=$row['year']?></b></td>
            </tr>
                <tr>
                    <td>Price:</td><td><b>
                        <?=number_format($row['price'], 2, '.',',')?>
                    </b></td>
                </tr>
            </tr>
        </table>
        <?php if(isset($_SESSION['uid'])): ?>
            <span>
                <a class="editMov" href="editfilm.php?
                id=<?=$row['filmID']?>">Edit</a>
                <a class="deleteMov" href="deletefilm.php?
                id=<?=$row['filmID']?>">Delete</a>
            </span>
        <?php endif ?>
    </div>
</div>
<?php endwhile; else: ?>
    <h1>No Uploaded Film At This Time</h1>
<?php endif; ?>