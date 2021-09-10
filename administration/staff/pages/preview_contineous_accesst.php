<?php
			include_once('../php/connection.php');
		 $class_id= mysqli_real_escape_string($conn,$_POST['cl']);
		
		 $subject_id= $_POST['sub'];
	 	$term_id= $_POST['tr'];
		
		$remark = 0;
		$last_num = 0;
		$nth = '';
		$tr = '';
		
		
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
				
				
					
					$check = mysqli_query($conn,"select * from  contineous_accessment where class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' and subject_id ='$subject_id' and status = '1' order by total desc");
					$num_rows_check = mysqli_num_rows($check);
					if($num_rows_check > 0){
						$sn = 1;
						while($check_array = mysqli_fetch_array($check)){
							$id = $check_array['id'];
								 $student_info_id = $check_array['student_info_id'];
								 $ca1 = $check_array['ca1'];
								 $ca2 = $check_array['ca2'];
								$ca3 = $check_array['ca3'];
								$total = $check_array['total'];
								$exam = $check_array['exam'];
								 $grade = $check_array['grade'];
									
														$sql_student_info = "select * from student_info where student_info_id = '$student_info_id'";
														$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
														$num_rows_student_info = mysqli_num_rows($query_student_info);		
															
															if($num_rows_student_info > 0){
																	$arrray_student_info = mysqli_fetch_array($query_student_info);
																	$first_name = $arrray_student_info['first_name'];
																	$last_name = $arrray_student_info['last_name'];
																	$other_name = $arrray_student_info['other_name'];
																	$gender = $arrray_student_info['gender'];
																
																	
																
																	//get full_name
																	 $full_name = $first_name.' '.$other_name.' '.$last_name;
																				
															}
															
											 //get postion for the current subject 
												$get_distinct_pos_sql = "select distinct total from  contineous_accessment where class_id = '$class_id' and session_id = '$session_id' ";
												$get_distinct_pos_sql .= "and term_id = '$term_id' and subject_id ='$subject_id' and status = '1' order by total desc";
												$get_distinct_pos_query = mysqli_query($conn,$get_distinct_pos_sql) or die(mysqli_error($conn));
												$get_distinct_pos_rows = mysqli_num_rows($get_distinct_pos_query);
												if($get_distinct_pos_rows > 0){
													$pos = 0;
													while($get_distinct_pos_arr = mysqli_fetch_array($get_distinct_pos_query)){
														$ttottal = $get_distinct_pos_arr['total'];
														$pos++;
														
														
														
															if($total==$ttottal){
																$sub_post = $pos;
																
																$sub_post_ln = substr($sub_post,strlen($sub_post)-1,strlen($sub_post));
																	
																	
																	if($sub_post_ln == 1){
																		$sub_post = $sub_post.'st';
																	}
																	else if($sub_post_ln == 2){
																		$sub_post = $sub_post.'nd';
																	}
																	else if($sub_post_ln == 3){
																		$sub_post = $sub_post.'rd';
																	}else{
																		$sub_post = $sub_post.'th';
																	}
															}
													}
												} //end of get postion for the current subject 
									
								$tr .='
									<tr>  <td width="1%"><strong>'.$sn.'</strong></td>
										  <td class="text-left" width="">'.$full_name.'</td>
										 <td class="text-center" width="">'.$gender.'</td>
										 <td class="text-center" width="">'.$ca1.'</td>
										 <td class="text-center" width="">'.$ca2.'</td>
										 <td class="text-center" width="">'.$ca3.'</td>
										 <td class="text-center" width="">'.$exam.'</td>
										 <td class="text-center" width="">'.$total.'</td>
										 <td class="text-center" width="">'.$grade.'</td>
										 <td class="text-center" width="">'.$sub_post.'</td>
										 
									</tr>';
									$sn++;
						}
						
					}
								
									
					
						
					
					mysqli_close($conn);

				?>
				

																						
																			
				<div id="ca_heading">
					<h1>PEACE SECONDARY SCHOOL</h1>
					<h2>P.O.Box 135,Rabba Road Mokwa Niger State</h2>
					<h2><span style="float:left;cursor:pointer" onclick="back_continuous_assessment()"><span class="glyphicon glyphicon-arrow-left"></span> Back</span><b style="color:red">CONTINUOUS ASSESSMENT REPORT BOOK </b></h2>
					<span style="float:right"><a href="php/print_continuous_accessment_sheet.php?cl=<?php echo $class_id;?>&sub=<?php echo $subject_id;?>&tr=<?php echo $term_id;?>" target="_BLANK" class="btn btn-primary"><span class="glyphicon glyphicon-print"> </span>  Print</a></span>
				</div>
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
																   <td class="text-center" width=""><strong>Remark</strong></td>
												
																  
																 </tr>
																</thead>
																<tbody>
																	<?php echo $tr; ?>
																
																</tbody>
																	
															</table>