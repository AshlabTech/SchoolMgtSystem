<?php  
	include_once('connection.php');
		  $staff_info_id= mysqli_real_escape_string($conn,$_POST['token']);
		 include_once('../../php/staff_data.php');
		 $class_id= mysqli_real_escape_string($conn,$_POST['cl']);
			
			$option = '';
			
			$class_section= "select * from classes where class_id = '$class_id' and status ='1' LIMIT 1";
			$class_section_run =  mysqli_query($conn,$class_section) or die(mysqli_error($conn));
			$class_section_num_rows =  mysqli_num_rows($class_section_run);
				if($class_section_num_rows > 0){
					$section_id_arr = mysqli_fetch_array($class_section_run);
					$section_id = $section_id_arr['school_section_id'];
				}
				
	if($staff_section==1){
				$sql_all_subject= "select * from subject where school_section <=2 and status ='1'";
																					$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
																					
																							while($all_subject_array = mysqli_fetch_array($php_process_sql_all_subject)){
																											$subject_id = $all_subject_array['id'];
																										$subject = $all_subject_array['subject'];
																										$subject_code = $all_subject_array['subject_code'];
																										$school_section = $all_subject_array['school_section'];
																										
																										$get_section_name  = mysqli_query($conn,"select * from school_section where school_section_id = '$school_section'") or die(mysqli_error($conn));
																										$row = mysqli_fetch_array($get_section_name);
																										$section_name = $row['abr'];
																								
																							echo  '<option value="'.$subject_id.'">'.$subject_code.'</option>';
																							}
	}else{
		$sub_check = "select * from staff_subjects where staff_info_id = '$staff_info_id' and section_id = '$section_id' and status ='1'";
			$sub_check_run =  mysqli_query($conn,$sub_check) or die(mysqli_error($conn));
			$num_rows_sub_check= mysqli_num_rows($sub_check_run);
			if($num_rows_sub_check > 0){
				$option .=  '<option value="">-- Select Subject --</option>';
					while($rows = mysqli_fetch_array($sub_check_run)){
						$id = $rows['id'];
						$subject_id = $rows['subject_id'];
								
								$sql_all_subject= "select * from subject where id = '$subject_id' and status ='1' LIMIT 1";
								$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
								
										$all_subject_array = mysqli_fetch_array($php_process_sql_all_subject);
													$subject_id = $all_subject_array['id'];
													$subject = $all_subject_array['subject'];
													$subject_code = $all_subject_array['subject_code'];
													$school_section = $all_subject_array['school_section'];
													
													$option .=  '<option value="'.$subject_id.'">'.$subject_code.'</option>';
					}
			}
	}
			
			
			mysqli_close($conn);
			echo $option;

?>