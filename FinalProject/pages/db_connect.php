<?php
$dbc = mysqli_connect("localhost","root","12345","students");

if (!$dbc) {
 die('Could not connect: '.mysql_error());
}


?>