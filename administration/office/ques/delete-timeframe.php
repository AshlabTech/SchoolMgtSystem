<?php
include_once('../../php/connection.php');

$id = $_POST['id'];

$qc = $conn->query("DELETE FROM `score_time_frame` WHERE  id=$id");
if ($qc) {
	echo 1;
}else{
	echo 0;
}

?>