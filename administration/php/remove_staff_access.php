<?php 
	$nav_id;
	$output='';
	$remove_access;
	$staff_info_id;
	include_once('connection.php');
	if(isset($_POST['token']) and isset($_POST['access_id'])){
		$staff_info_id = $_POST['token'];
		$nav_id = $_POST['access_id'];
		
		
		$remove_access = mysqli_query($conn,"update staff_access set status = '0' where staff_info_id = '$staff_info_id' and nav_id = '$nav_id'") or die(mysqli_error($conn));
		$output =  1;
			
		mysqli_close($conn);
		echo $output;
	}

		
?>