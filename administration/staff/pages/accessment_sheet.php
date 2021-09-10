<?php
			include_once('../php/connection.php');
		 $class_id= mysqli_real_escape_string($conn,$_POST['cl']);
		
		 $subject_id= $_POST['sub'];
	 	$term_id= $_POST['term_id'];
		
		$class_section= "select * from classes where class_id = '$class_id' and status ='1' LIMIT 1";
			$class_section_run =  mysqli_query($conn,$class_section) or die(mysqli_error($conn));
			$class_section_num_rows =  mysqli_num_rows($class_section_run);
				if($class_section_num_rows > 0){
					$section_id_arr = mysqli_fetch_array($class_section_run);
					$section_id = $section_id_arr['school_section_id'];
					$class_name = $section_id_arr['class_name'];
				}
				
				$sql_all_subject= "select * from subject where id = '$subject_id' and status ='1' LIMIT 1";
								$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
								
										$all_subject_array = mysqli_fetch_array($php_process_sql_all_subject);
													$subject_id = $all_subject_array['id'];
													$subject = $all_subject_array['subject'];
													$subject_code = $all_subject_array['subject_code'];
													$school_section = $all_subject_array['school_section'];
		//GET SESSION ID
				$session_query = mysqli_query($conn,"select * from session where status = '1'") or die(mysqli_error($conn));
				$session_array = mysqli_fetch_array($session_query);
				$session_id = $session_array['section_id'];
				$session = $session_array['section'];
				
				
				$query = mysqli_query($conn,"SELECT student_info_id FROM student_classes WHERE class_id = '$class_id' AND status != '0'  ");
				$num_rows_all_class = mysqli_num_rows($query);
					if($num_rows_all_class > 0){
						 $sn = 1;
						while($array_all_class = mysqli_fetch_array($query)){
						$student_info_id = $array_all_class['student_info_id'];
													
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
											
											//GET Age
												$dob_s = strtotime($date_of_birth);
											$current_date_s = strtotime(@date('Y-m-d'));
											$age_diff = $current_date_s - $dob_s;
											//$age_minute = $age_diff/60;
											$age = ceil($age_diff/(60*60*24*365));
											if($age < 1){
												$age = '<b style="color:red">not valid</b>';
											}else{
												$age = $age.'yrs';
											}
											//get full_name
											 $full_name = $first_name.' '.$other_name.' '.$last_name;
														
									}
					$ca1 = 0;
					$ca2 = 0;
					$ca3 = 0;
					$exam = 0;
					$grade = 0;
					$total = 0;
					$tr = '';
					$check = mysqli_query($conn,"select * from  contineous_accessment where student_info_id='$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' and subject_id ='$subject_id' and status = '1'");
					$num_rows_check = mysqli_num_rows($check);
					if($num_rows_check > 0){
						$check_array = mysqli_fetch_array($check);
						$id = $check_array['id'];
						 $ca1 = $check_array['ca1'];
						 $ca2 = $check_array['ca2'];
						$ca3 = $check_array['ca3'];
						$total = $check_array['total'];
						$exam = $check_array['exam'];
						 $grade = $check_array['grade'];
					}
							
					
									
						$tr .='
									<tr>  <td width="1%"><strong>'.$sn++.'</strong></td>
										  <td class="text-left" width="">'.$full_name.'</td>
										 <td class="text-center" width="">'.$gender.'</td>
										 <td class="text-center" width=""><input type="text" value ="'.$ca1.'" style="width:50px" id="c1_'.$student_info_id.'" onblur="submit_accessment('.$student_info_id.')"></td>
										 <td class="text-center" width=""><input type="text" value ="'.$ca2.'"  style="width:50px" id="c2_'.$student_info_id.'" onblur="submit_accessment('.$student_info_id.')"></td>
										 <td class="text-center" width=""><input type="text" value ="'.$ca3.'"  style="width:50px" id="c3_'.$student_info_id.'" onblur="submit_accessment('.$student_info_id.')"></td>
										 <td class="text-center" width=""><input type="text" value ="'.$exam.'"  style="width:50px" id="exam_'.$student_info_id.'" onblur="submit_accessment('.$student_info_id.')"></td>
										 <td class="text-center" width="" id="total'.$student_info_id.'">'.$total.'</td>
										 <td class="text-center" width="" id="grade'.$student_info_id.'">'.$grade.'</td>
									</tr>';
						}
					}else{
										 $tr = "no student in the selected class...";
										
				}
					mysqli_close($conn);

				?>
				
				<h3 style="text-transform:uppercase">
					<i class="ace-icon fa fa-users home-icon" style="margin-left:20px;"></i>
					<a href="#">  <b><?php echo $class_name; ?> ACCESSMENT SHEET - 
					<strong style="color:red"><?php echo $subject; ?></strong> 
					(<?php echo $session; ?> ACADEMIC SESSION)</b>
					</a>
					<span style="float:right">
						<button class="btn btn-primary" onclick="preview_contineous_accesst(<?php echo $class_id.','.$subject_id.','.$term_id ?>)"><span class="glyphicon glyphicon-eye-open"> </span>  Preview</button>
						<a href="php/print_continuous_accessment_sheet.php?cl=<?php echo $class_id;?>&sub=<?php echo $subject_id;?>&tr=<?php echo $term_id;?>" target="_BLANK" class="btn btn-primary"><span class="glyphicon glyphicon-print"> </span>  Print</a>
					</span>
				</h3>
																						
																			
												
															 <table class="table table-bordered table-hover" width="100%" border="1px">
															<thead>
																 <tr>
																   <td width="1%"><strong>S/N</strong></td>
																   
																   <td class="text-center" width=""><strong>FULL NAME</strong></td>
																   <td class="text-center" width=""><strong>GENDER</strong></td>
																   <td class="text-center" width=""><strong>C1</strong></td>
																   <td class="text-center" width=""><strong>C2</strong></td>
																   <td class="text-center" width=""><strong>C3</strong></td>
																   <td class="text-center" width=""><strong>Exam</strong></td>
																   <td class="text-center" width=""><strong>Total</strong></td>
																   <td class="text-center" width=""><strong>Grade</strong></td>
												
																  
																 </tr>
																</thead>
																<tbody>
																	<?php echo $tr; ?>
																
																</tbody>
																	
															</table>