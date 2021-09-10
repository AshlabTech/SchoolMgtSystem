<?php 
		include_once('connection.php');
		$staff_info_id = $_POST['token'];
		
			$check_if_activated = mysqli_query($conn,"select * from staff_login_info where staff_info_id = '$staff_info_id'  and status = '1'");
			$check_if_activated_rows = mysqli_num_rows($check_if_activated);
				if($check_if_activated_rows > 0){
						$sql_update = "UPDATE staff_login_info SET status = '0' where staff_info_id = '$staff_info_id' and status = '1'";
						$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
						echo 'Account is Deactivated...';
				}else{
						$sql_update = "UPDATE staff_login_info SET status = '1' where staff_info_id = '$staff_info_id'";
						$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
						echo 'Account is Activated...';
				}
?>