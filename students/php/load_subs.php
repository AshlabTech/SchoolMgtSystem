<?php  
	include_once('connection.php');
		 // $staff_info_id= mysqli_real_escape_string($conn,$_POST['token']);
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
				
	if($section_id==1 or $section_id==2){
				$sql_all_subject= "select * from subject where school_section <=2 and status ='1' order by subject_code";
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
		
								
								$sql_all_subject= "select * from subject where school_section >2 and status ='1' order by subject_code ";
								$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
								
										while($all_subject_array = mysqli_fetch_array($php_process_sql_all_subject)){
												$subject_id = $all_subject_array['id'];
													$subject = $all_subject_array['subject'];
													$subject_code = $all_subject_array['subject_code'];
													$school_section = $all_subject_array['school_section'];
													
													$option .=  '<option value="'.$subject_id.'">'.$subject_code.'</option>';
										}
												

	}
			
			
			mysqli_close($conn);
			echo $option;

?>