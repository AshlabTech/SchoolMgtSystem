<?php session_start(); ?>
<?php
include_once('../php/connection.php');


$id = $_POST['id'];

$sql = "UPDATE student_info SET status ='1' WHERE student_info_id='$id'";
$run = $conn->query($sql);
echo 200;
?>

