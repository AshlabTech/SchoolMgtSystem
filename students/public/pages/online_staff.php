
<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../staff_login.php');
	}
	
	$get_onlines = mysqli_query($conn,"select * from online_staffs where staff_info_id !='$staff_info_id' and status = '1'");
						if(mysqli_num_rows($get_onlines) > 0){
							
								echo '<ul class="list-group" style="margin:10px 0px;border-radius:0;box-shadow:none">';
								echo ' <li class="list-group-item active"><span class="fa fa-users" style="margin-right:10px"> </span> Online Staffs</li>';
							while($onlines = mysqli_fetch_array($get_onlines)){
								$id = $onlines['staff_info_id'];
							
									$online_staffs_sql = "select * from staff_info where staff_info_id = '$id'";
				
									$online_staffs =  mysqli_query($conn,$online_staffs_sql) or die(mysqli_error($conn));
									$num_rows_all_staff = mysqli_num_rows($online_staffs);
										if($num_rows_all_staff > 0){
											$staff_info_arr = mysqli_fetch_array($online_staffs);	
												$staff_id = $staff_info_arr['staff_info_id'];
												$first_name = $staff_info_arr['first_name'];
												$last_name = $staff_info_arr['last_name'];
												$last_name = $staff_info_arr['last_name'];
												$name = $first_name.'  '.$last_name;
												echo   '<li class="list-group-item"> <span class="fa fa-circle text-success" style="font-size:9px;margin-right:10px"> </span>     '.$name.' </li>';
										}
							
								
							}
							echo '</ul>';
	

						}
?>

