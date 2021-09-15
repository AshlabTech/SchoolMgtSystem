<?php
	session_start();
	include_once('connection.php');
	$staff_pass = mysqli_real_escape_string($conn,$_POST['staff_pass']);
	$staff_id = mysqli_real_escape_string($conn,$_POST['staff_id']);
	$password = md5($school_abbr.'1234');

					$sql = "SELECT * FROM  staff_login_info where staff_login_id = '$staff_id' and password = '$password' and status = '1'";
					$query= mysqli_query($conn,$sql) or die(mysqli_error($conn));
					$num_rows = mysqli_num_rows($query);
					if($num_rows > 0){
						$staff_array = mysqli_fetch_array($query);
						$staff_info_id = $staff_array['staff_info_id'];
						
						//put staff online
						//check is the staff details in the online staffs table before
						$check = mysqli_query($conn,"select * from online_staffs where staff_info_id = '$staff_info_id'");
						if(mysqli_num_rows($check) > 0){
							$update = mysqli_query($conn,"update online_staffs set status = '1' where staff_info_id = '$staff_info_id'");
								
						}else{
							$get_online = mysqli_query($conn,"insert into online_staffs(staff_info_id) values ('$staff_info_id')") or die(mysqli_error($conn));
								
						}
						
							$_SESSION['staff_info_id'] = $staff_info_id;
							echo 1;
					
					}else{
						echo '<span style="color:red">Incorrect login ID or Password..</span>';
					}
		

	
?>