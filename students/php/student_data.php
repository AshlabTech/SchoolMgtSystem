<?php 

	$sql_student_info = "select * from student_info where student_info_id = '$student_info_id'";
								$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
								$num_rows_student_info = mysqli_num_rows($query_student_info);		
									
									if($num_rows_student_info > 0){
											$arrray_student_info = mysqli_fetch_array($query_student_info);
											$first_name = $arrray_student_info['first_name'];
											$last_name = $arrray_student_info['last_name'];
											$other_name = $arrray_student_info['other_name'];
											$gender = $arrray_student_info['gender'];
											$religion = $arrray_student_info['religion'];
											$date_of_birth = $arrray_student_info['date_of_birth'];
											$state_id = $arrray_student_info['state_id'];
											$lga_id = $arrray_student_info['lga_id'];
											$tribe = $arrray_student_info['tribe'];
											$image_name = $arrray_student_info['image_name'];
											$residential_address = $arrray_student_info['residential_address'];
											$guidian_name = $arrray_student_info['guidian_name'];
											$guidian_phone_number = $arrray_student_info['guidian_phone_number'];
											$guadian_relationship = $arrray_student_info['guadian_relationship'];
											$guidian_other_phone_number = $arrray_student_info['guidian_other_phone_number'];
											$guidian_address = $arrray_student_info['guidian_address'];
											$guidain_occupation = $arrray_student_info['guidain_occupation'];
											$previous_school = $arrray_student_info['previous_school'];
											$reason_for_leaving_the_school = $arrray_student_info['reason_for_leaving_the_school'];
											$date_enrolled = $arrray_student_info['date_enrolled'];
											$student_status = $arrray_student_info['status'];
																		
												//GET Age
											$dob_s = strtotime($date_of_birth);
											$current_date_s = strtotime(@date('Y-m-d'));
											$age_diff = $current_date_s - $dob_s;
											//$age_minute = $age_diff/60;
											$age = ceil($age_diff/(60*60*24*365));
											//get full_name
											$full_name = $first_name.' '.$other_name.' '.$last_name;
											
									//student class 
									$get_class = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' and (status ='1' or  status ='2') ");
									$class_id_array = mysqli_fetch_array($get_class);
									$class_id = $class_id_array['class_id'];
											
														//get state title
						$sql_get_state=mysqli_query($conn,"SELECT * FROM states where state_id = '$state_id'") or die(mysql_error());
						if($sql_get_state){
							$sql_get_state_row=mysqli_num_rows($sql_get_state);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql_get_state)){
									$state_name = $row['name'];
								}}}
							
					$sql=mysqli_query($conn,"SELECT *FROM lga where local_id = '$lga_id'") ;
						if($sql){
							$sql_get_state_row=mysqli_num_rows($sql);
							if($sql_get_state_row > 0){
								while($row=mysqli_fetch_assoc($sql)){
									$lga_title = $row['title'];
								}}}
														
															if($image_name == ''){
															if($gender == 'M')
																$user_pic = '../images/default.jpg';
															else
																$user_pic = '../images/default_f.jpg';
														}else{
															
															if(file_exists("../students_image_upload/$image_name") == 1){
																$user_pic = "../students_image_upload/$image_name";
																}else{
																	if($gender == 'M')
																		$user_pic = '../images/default.jpg';
																	else
																		$user_pic = '../images/default_f.jpg';
																}
														}
									}
?>