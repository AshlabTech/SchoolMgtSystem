<?php 
			include_once('connection.php');
			
			if(isset($_POST['token']) and isset($_POST['cl'])){
				$staff_info_id = $_POST['token'];
				$class_id = $_POST['cl'];
			}
			
			//get session id 
							$query2 = mysqli_query($conn,"select * from session where status = '1'") or die(mysqli_error($conn));
							while($class_array2 = mysqli_fetch_array($query2)){
								$session_id = $class_array2['section_id'];
								$session = $class_array2['section'];
							}
			//check if exist but status = 1			
			$check_if_assigned = mysqli_query($conn,"select * from staff_classes where staff_info_id = '$staff_info_id' and class_id = '$class_id' and status = '1'");
			$check_if_assigned_num_rows = mysqli_num_rows($check_if_assigned);
				
				
				if($check_if_assigned_num_rows <= 0){ // if do not exist turn on
						//check if exist but status = 0
						$check_if_assigned2 = mysqli_query($conn,"select * from staff_classes where staff_info_id = '$staff_info_id' and class_id = '$class_id' and status = '0'");
							$check_if_assigned_num_rows2 = mysqli_num_rows($check_if_assigned2);
							
								if($check_if_assigned_num_rows2 <= 0){ // if do not exist at all then add
									$insert_query = mysqli_query($conn,"insert into staff_classes(staff_info_id,class_id,session_id) values ('$staff_info_id','$class_id','$session_id')") or die(mysqli_error($conn));
												
												if($insert_query){
														
													echo  'Class is assigned successfully...';
												}else{
													echo  '	Operation failed.....';
												}
												
								}else{ // if exist but turn off before then turn on back
										$sql_update2 = "UPDATE staff_classes SET status ='1' where staff_info_id = '$staff_info_id' and class_id = '$class_id'";
										$query_update2 = mysqli_query($conn,$sql_update2) or die(mysqli_error($conn));
											if($query_update2){
												echo  'Class is assigned successfully...';
											}else{
													echo  '	Operation failed.....';
											}
								}
						
				
				}else{ // if yes it already exist  turn off
					$sql_update = "UPDATE staff_classes SET status ='0' where staff_info_id = '$staff_info_id' and class_id = '$class_id'";
					$query_update = mysqli_query($conn,$sql_update) or die(mysqli_error($conn));
						if($query_update){
								
							echo  'Removed successfully...';
						}else{
							echo  '	Removing failed.....';
						}
				}
			
		
?>