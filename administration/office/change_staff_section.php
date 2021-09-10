<?php
include_once('../php/connection.php');


$id = $_POST['id'];
$secid = $_POST['sec'];			

		
		$deleted =  mysqli_query($conn,"UPDATE staff_info SET section=$secid WHERE staff_info_id = '$id' ") or die(mysqli_error($conn));
		if ($deleted) {
			echo 200;
		}else{
			echo 201;
		}
?>
