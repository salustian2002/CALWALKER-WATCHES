<?php

$conn = mysqli_connect("localhost","root","");

if(!$conn) {
    die("Could not connect to mysqli".mysqli_connect_error());
}

mysqli_select_db($conn, 'webdb');
?>