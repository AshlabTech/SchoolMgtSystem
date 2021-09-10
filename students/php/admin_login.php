<?php
	session_start();
	include_once('connection.php');
	$admin_login_id = mysqli_real_escape_string($conn,$_POST['admin_login_id']);
	$admin_password = mysqli_real_escape_string($conn,$_POST['admin_password']);
	$password = md5($admin_password);

					$sql = "SELECT * FROM staff_login_info where staff_login_id = '$admin_login_id' and password = '$password' and status = '1'";
					$query= mysqli_query($conn,$sql);
					$num_rows = mysqli_num_rows($query);
					if($num_rows > 0){
						$admin_array = mysqli_fetch_array($query);
						$staff_info_id = $admin_array['staff_info_id'];
						$type = $admin_array['type'];
						$_SESSION['staff_info_id'] = $staff_info_id;
						$_SESSION['type'] = $type;
						
						//put staff online
						//check is the staff details in the online staffs table before
						$check = mysqli_query($conn,"select * from online_staffs where staff_info_id = '$staff_info_id'");
						if(mysqli_num_rows($check) > 0){
							$update = mysqli_query($conn,"update online_staffs set status = '1' where staff_info_id = '$staff_info_id'");
								
						}else{
							$get_online = mysqli_query($conn,"insert into online_staffs(staff_info_id,status) values ('$staff_info_id','1')") or die(mysqli_error($conn));
								
						}
						
			
							echo 1;
			
					}else{
						echo '<span style="color:red">Incorrect login ID or Password..</span>';
					}
		

	
?>