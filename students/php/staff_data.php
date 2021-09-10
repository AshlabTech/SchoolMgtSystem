<?php 
			include_once('connection.php');
			
						
					//get the staff id from the staff_info table
				//get the staff id from the staff_info table
				$sql_all_staff = "select * from staff_info  where staff_info_id = '$staff_info_id'";
				
				$php_process_sql_all_staff =  mysqli_query($conn,$sql_all_staff) or die(mysqli_error($conn));
				$num_rows_all_staff = mysqli_num_rows($php_process_sql_all_staff);
					if($num_rows_all_staff > 0){
							$sn = 1;
						while($all_staff_array = mysqli_fetch_array($php_process_sql_all_staff)){
							$staff_info_id = $all_staff_array['staff_info_id'];
							$first_name = $all_staff_array['first_name'];
							$last_name = $all_staff_array['last_name'];
							$other_name = $all_staff_array['other_name'];
							$gender = $all_staff_array['gender'];
							$religion = $all_staff_array['religion'];
							$marital_status = $all_staff_array['marital_status'];
							$date_of_birth = $all_staff_array['date_of_birth'];
							$state_id = $all_staff_array['state_id'];
							 $lga_id = $all_staff_array['lga_id'];
							$tribe = $all_staff_array['tribe'];
							$email_address = $all_staff_array['email_address'];
							$phone_number = $all_staff_array['phone_number'];
							$other_phone_number = $all_staff_array['other_phone_number'];
							$residential_address = $all_staff_array['residential_address'];
							$postal_code = $all_staff_array['postal_code'];
							$next_of_kin = $all_staff_array['next_of_kin'];
							$next_of_kin_phone_number = $all_staff_array['next_of_kin_phone_number'];
							$relationship_with_next_of_kin = $all_staff_array['relationship_with_next_of_kin'];
							$next_of_kin_residential_address = $all_staff_array['next_of_kin_residential_address'];
							$next_of_kin_postal_code = $all_staff_array['next_of_kin_postal_code'];
							$highest_qualification = $all_staff_array['highest_qualification'];
							$level = $all_staff_array['level'];
							$school = $all_staff_array['school'];
							$date_obtained = $all_staff_array['date_obtained'];
							$refree = $all_staff_array['refree'];
							$refree_hone_number = $all_staff_array['refree_hone_number'];
							$date_staff_employed = $all_staff_array['date_staff_employed'];
							$image_name = $all_staff_array['image_name'];
							$staff_section = $all_staff_array['section'];
							$staff_login_id = $all_staff_array['staff_login_id']; 
							$staff_status = $all_staff_array['status']; 
				
						
														//get state title
						$sql_get_state=mysqli_query($conn,"SELECT * FROM states where state_id = '$state_id'") or die(mysql_error());
						if($sql_get_state){
							$sql_get_state_row=mysqli_num_rows($sql_get_state);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql_get_state)){
									 $state_name = $row['name'];
								}}}
							
					$sql=mysqli_query($conn,"SELECT *FROM lga where local_id = '$lga_id'") ;
					
							$sql_get_state_row=mysqli_num_rows($sql);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql)){
									$lga_title = $row['title'];
								}}
							
							//staff login_id
							$sql_all_staff_login_id = "select * from  staff_login_info where staff_info_id = '$staff_info_id'";
							$php_process_sql_all_staff_login_id =  mysqli_query($conn,$sql_all_staff_login_id) or die(mysqli_error($conn));
							$num_rows_all_staff_login_id = mysqli_num_rows($php_process_sql_all_staff_login_id);
							$staff_info_login_id_array = mysqli_fetch_assoc($php_process_sql_all_staff_login_id);
							$staff_login_id = $staff_info_login_id_array['staff_login_id'];
							$full_name = $first_name.' '.$last_name.' '.$other_name;
							
							
															if($image_name == ''){
															if($gender == 'M')
																$user_pic = '../staff_image_uploads/default.jpg';
															else
																$user_pic = '../staff_image_uploads/default_f.jpg';
														}else{
															
															if(file_exists("../staff_image_uploads/$image_name") == 1){
																$user_pic = "../staff_image_uploads/$image_name";
																}else{
																	if($gender == 'M')
																		$user_pic = '../staff_image_uploads/default.jpg';
																	else
																		$user_pic = '../staff_image_uploads/default_f.jpg';
																}
														}
							
						}
					}
					
		
		
		
			
			

?>			