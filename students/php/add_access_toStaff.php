<?php 

	
	$nav_id;
	$output='';
	$add_access;
	$update_status;
	$staff_info_id;
	
	
	include_once('connection.php');
	if(isset($_POST['token']) and isset($_POST['access_id'])){
		$staff_info_id = $_POST['token'];
		$nav_id = $_POST['access_id'];
		
		$check_staff_access =  mysqli_query($conn,"select * from staff_access where staff_info_id = '$staff_info_id' and nav_id = '$nav_id'") or die(mysqli_error($conn));
		$check_staff_access_num_rows =  mysqli_num_rows($check_staff_access);
		
			if($check_staff_access_num_rows > 0){
				$update_status = mysqli_query($conn,"update staff_access set status = '1' where staff_info_id = '$staff_info_id' and nav_id = '$nav_id'") or die(mysqli_error($conn));
				$output =  1;
			}else{
				$add_access = mysqli_query($conn,"insert into staff_access (staff_info_id,nav_id,status) values('$staff_info_id','$nav_id','1')") or die(mysqli_error($conn));
				$output = 1;
			}
			mysqli_close($conn);
			echo $output;
	}

		
?>