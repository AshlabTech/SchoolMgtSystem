<?php
include("../php/dbconnect.php");

$tid = $_POST['id'];
$bid = $_POST['cid'];
$run = $conn->query("UPDATE branch SET form_teacher_id='$tid' WHERE id='$bid'");
if ($run) {
	echo 1;
}else{
	echo 0;
}


?>