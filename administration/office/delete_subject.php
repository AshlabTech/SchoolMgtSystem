<?php
include_once('../php/connection.php');


$id = $_POST['id'];
			
		$delete= "update subject set status='0' where id = $id";
		$deleted =  mysqli_query($conn,$delete) or die(mysqli_error($conn));
		if ($deleted) {
			echo 200;
		}else{
			echo 201;
		}
?>
