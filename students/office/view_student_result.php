<?php 

	if(isset($_GET['token']) and isset($_GET['cl']) and isset($_GET['tr']) and isset($_GET['se']) ){
		
	require_once('../php/fpdf181/fpdf.php');
	session_start();
	include_once('../php/connection.php');
	
		$ss;
		$remark = 0;
		$last_num = 0;
		$nth = '';
		$term_id;
		$class_id;
		$sub_post;
		$sub_post_ln;
		$class_idd;
		$hass_class_idd;
		$session;
		$session_id;
		$class_section;
		$hash_student_info_idd;
		$student_info_id;
		$hash_student_info_id;
		$class_section_run;
		$hash_class_id;
		$section_id_arr;
		$total_attendance=0;
		$total_absent_inschool=0;
		$total_marked_attendace=0;
		$class_section_num_rows;
	
		 $hash_student_info_id= mysqli_real_escape_string($conn,$_GET['token']);
		 $hash_class_id= mysqli_real_escape_string($conn,$_GET['cl']);
		 $session_id= mysqli_real_escape_string($conn,$_GET['se']);
		 $term_id= mysqli_real_escape_string($conn,$_GET['tr']);
		
	
		
		//Get real class id
			$class_section= "select * from classes where  status ='1'";
			$class_section_run =  mysqli_query($conn,$class_section) or die(mysqli_error($conn));
			$class_section_num_rows =  mysqli_num_rows($class_section_run);
				if($class_section_num_rows > 0){
					while($section_id_arr = mysqli_fetch_array($class_section_run)){
							$class_idd = $section_id_arr['class_id'];
							$hass_class_idd = md5($class_idd);
								
								if($hash_class_id == $hass_class_idd){
										$class_id = $class_idd;
										$section_id = $section_id_arr['school_section_id'];
										$class_name = $section_id_arr['class_name'];
								}
						
					}
				
				}
		// Get term result ready for the class 
			include_once('../php/get_term_ready.php');
			
			
		//get student real id 
				$sql_student_info = "select * from student_info";
				$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
				$num_rows_student_info = mysqli_num_rows($query_student_info);		
															
				if($num_rows_student_info > 0){
					while($arrray_student_info = mysqli_fetch_array($query_student_info)){
						$student_info_idd = $arrray_student_info['student_info_id'];
						$hash_student_info_idd = md5($student_info_idd);
						
						if($hash_student_info_id == $hash_student_info_idd){
							$student_info_id = $student_info_idd;
							$first_name = $arrray_student_info['first_name'];
							$last_name = $arrray_student_info['last_name'];
							$other_name = $arrray_student_info['other_name'];
							$gender = $arrray_student_info['gender'];
					
							//get full_name
							$full_name = $first_name.' '.$other_name.' '.$last_name;
						}
					}
					
				}
				
			//get total attendance for the term
				$total_attendance_sql  = "select * from student_attendance where student_info_id = '$student_info_id'";
				$total_attendance_sql .= "and session_id = '$session_id' and term_id = '$term_id'";
				$total_attendance_sql .= "and class_id = '$class_id' and status = '1'";
				
				$total_attendance_query = mysqli_query($conn,$total_attendance_sql) or die(mysqli_error($conn));
				$total_attendance = mysqli_num_rows($total_attendance_query);
				
				//get total number of days out of school 
				$total_att  = "select distinct year from student_attendance where session_id = '$session_id'";
				$total_att  .= "and class_id = '$class_id' and term_id = '$term_id'";
				$total_att_run = mysqli_query($conn,$total_att) or die(mysqli_error($conn));
				$total_att_num_rows = mysqli_num_rows($total_att_run);
					if($total_att_num_rows > 0){
						while($total_att_num_arr = mysqli_fetch_array($total_att_run)){
							$yr = $total_att_num_arr['year'];
							
							$total_att2  = "select distinct month from student_attendance where session_id = '$session_id'";
							$total_att2  .= "and class_id = '$class_id' and term_id = '$term_id' and year ='$yr'";
							$total_att_run2 = mysqli_query($conn,$total_att2) or die(mysqli_error($conn));
							$total_att_num_rows2 = mysqli_num_rows($total_att_run2);
							
								if($total_att_num_rows2 > 0){
									while($total_att_num_arr2 = mysqli_fetch_array($total_att_run2)){
										$mn = $total_att_num_arr2['month'];
										
										$total_att3  = "select distinct day from student_attendance where session_id = '$session_id'";
										$total_att3  .= "and class_id = '$class_id' and term_id = '$term_id' and year ='$yr' and month = '$mn'";
										$total_att_run3 = mysqli_query($conn,$total_att3) or die(mysqli_error($conn));
										$total_att_num_rows3 = mysqli_num_rows($total_att_run3);
											if($total_att_num_rows3 > 0){
												while($total_att_num_arr3 = mysqli_fetch_array($total_att_run3)){
													
													$total_marked_attendace++;
												}
											}
									}
									
								}else{
									$mn=888;
								}
						}
						
						$total_absent_inschool = $total_marked_attendace - $total_attendance;
					} //end of total number of days out of school 
				
		//GET SESSION ID
				$session_query = mysqli_query($conn,"select * from session where section_id = '$session_id'") or die(mysqli_error($conn));
				$session_array = mysqli_fetch_array($session_query);
				$session_id = $session_array['section_id'];
				$session = $session_array['section'];
				
				
			//number of students in a class 
				
				$number_inclass_query =  mysqli_query($conn,"select * from student_classes where class_id = '$class_id' and (status ='1' or status = '2')") or die(mysqli_error($conn));
				$number_in_class =  mysqli_num_rows($number_inclass_query);
				
				
			//get position
			$position='';
			$position1;
			if($term_id==3){
												$postion_sql = mysqli_query($conn,"select distinct average_score from  yearly_result where class_id = '$class_id' and session_id = '$session_id' and  status = '1' order by average_score desc");
													$postion_sql_num_rows = mysqli_num_rows($postion_sql);
													if($postion_sql_num_rows>0){
														$position1 = 1;
														while($position_row = mysqli_fetch_array($postion_sql)){
															
															$average_score = $position_row['average_score'];
															
																
																$position_sql = mysqli_query($conn,"select * from yearly_result where class_id = '$class_id' and session_id = '$session_id' and average_score ='$average_score' and  status = '1'");
																$position_rows = mysqli_num_rows($position_sql);
																if($position_rows>0){
																	while($myrrows=mysqli_fetch_array($position_sql)){
																		$student_info_iddd = $myrrows['student_info_id'];
																		if($student_info_iddd==$student_info_id){
																			$position = $position1;
																		}
																	}
																}
																$position1++;
																	
														}
													}
			}else{
				$position_sql = mysqli_query($conn,"select * from  term_result where student_info_id='$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' and status = '1'");
						$position_rows = mysqli_num_rows($position_sql);
						if($position_rows>0){
							$position_arr = mysqli_fetch_array($position_sql);
							$position = $position_arr['position'];
							
						}
			}
										    if(!empty($position)){
												$position_ln = substr($position,strlen($position)-1,strlen($position));
												if($position_ln == 1){
													$position = $position.'st';
												}
												else if($position_ln == 2){
													$position = $position.'nd';
												}
												else if($position_ln == 3){
													$position = $position.'rd';
												}else{
													$position = $position.'th';
												}
											}
											
						
		// DETERMINE STUDENT SECTION
			if($section_id==1){
				$ss = 'NURSERY PUPILS';
			}
			else if($section_id==2){
					$ss = 'PRIMARY PUPILS';
			}
			else if($section_id==3){
				$ss = 'JUNIOR SECONDARY SCHOOL (J.S.S)';
			}
			else if($section_id==4){
					$ss = 'SENIOR SECONDARY SCHOOL (S.S.S)';
			}
				
				
	//DETERMINE THE TERM 
		if($term_id ==1){
			$tr = '1st TERM';
		}else if($term_id ==2){
			$tr = '2nd TERM';
		}
		else if($term_id ==3){
			$tr = '3rd TERM';
		}

	// Create instance of the class_alias
	$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));
	
	
 
	$pdf-> setTitle('pss');
	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf->AliasNbPages();

	$pdf  -> AddPage('P','A4',360);

	
	//heading 
	$pdf->Image('../images/logo_pss.png',175 ,7,20,20);
	$pdf-> setTextColor(27,186,226);
	$pdf-> setFont('Arial','B','26');
	$pdf-> Cell(0,10,'PEACE SECONDARY SCHOOL',0,1,'C');
	$pdf-> setFont('Arial','B','14');
	$pdf-> Cell(0,8,'P.O.Box 135,Rabba Road Mokwa,  Niger State',0,1,'C');
	$pdf-> setTextColor(209,20,29);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,'CONTINUOUS ASSESSMENT REPORT BOOK FOR '.$ss,0,1,'C');
	$pdf-> setTextColor(0,0,0);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,''.$subject,0,1,'C');
	
	$pdf->Ln(2);
	$pdf-> setTextColor(27,186,226);
	$pdf->SetLineWidth(0.3);
			$pdf->SetDrawColor(47,186,226);
			$pdf->Line(52,48,200,48);
			
			
			
			
			
		$pdf-> setFont('Arial','','12');
		$pdf-> Cell(42,8,'NAME OF STUDENT: ',0,0,'C');
		$pdf-> Cell(0,8,''.strtoupper($full_name),0,1,'L');
			
			$pdf->Line(55,56,99,56);
			$pdf->Line(125,56,160,56);
			$pdf->Line(180,56,200,56);
			
		//$pdf-> setFont('Arial','','9');
		$pdf-> Cell(45,8,'ADMISSION NUMBER:',0,0,'C');
		$pdf-> Cell(45,8,'',0,0,'L');
		$pdf-> Cell(25,8,'SESSION:',0,0,'C');
		$pdf-> Cell(35,8,''.$session,0,0,'L');
		$pdf-> Cell(20,8,'TERM:',0,0,'C');
		$pdf-> Cell(0,8,''.$tr,0,1,'L');
		
		$pdf->Ln(0);
		
			$pdf->Line(28,64,75,64);
			$pdf->Line(95,64,130,64);
			$pdf->Line(175,64,200,64);
			
			
			
		$pdf-> Cell(20,8,'HOUSE:',0,0,'L');
		$pdf-> Cell(45,8,'',0,0,'L');
		$pdf-> Cell(20,8,'CLASS :',0,0,'C');
		$pdf-> Cell(35,8,''.$class_name,0,0,'L');
		$pdf-> Cell(45,8,'NUMBER IN CLASS:',0,0,'C');
		$pdf-> Cell(0,8,''.$number_in_class,0,1,'C');
		
		
		$pdf->Line(64,72,110,72);
		$pdf->Line(138,72,200,72);
		$pdf-> Cell(58,8,'ATTENDANCE FOR TERM:',0,0,'L');
		$pdf-> Cell(45,8,''.$total_attendance,0,0,'C');
		$pdf-> Cell(25,8,'DAYS OUT:',0,0,'L');
		$pdf-> Cell(0,8,''.$total_absent_inschool,0,1,'C');
		
		$pdf->Line(64,80,110,80);
		$pdf->Line(138,80,200,80);
		$pdf-> Cell(58,8,'AVERAGE PERCENTAGE:',0,0,'L');
		$pdf-> Cell(45,8,'',0,0,'L');
		$pdf-> Cell(25,8,'POSITION:',0,0,'L');
		$pdf-> Cell(0,8,''.$position,0,1,'C');
	
		$pdf->Ln(5);
			$pdf-> setFont('Arial','B','14');
			$pdf-> Cell(0,8,'COGNITIVE DOMAIN',0,1,'C');
		$pdf->Ln(2);
		
			if($term_id==3)
			{
				$pdf-> setFont('Arial','B','9');
				$pdf-> Cell(54,8,'SUBJECTS',1,0,'C');
				$pdf-> Cell(9,8,'CA1',1,0,'C');
				$pdf-> Cell(9,8,'CA2',1,0,'C');
				$pdf-> Cell(10,8,'Test',1,0,'C');
				$pdf-> Cell(13,8,'Exam',1,0,'C');
				$pdf-> Cell(16,8,'Term1 %',1,0,'C');
				$pdf-> Cell(16,8,'Term2 %',1,0,'C');
				$pdf-> Cell(16,8,'Term3 %',1,0,'C');
				$pdf-> Cell(13,8,'Grade',1,0,'C');
				$pdf-> Cell(16,8,'Position',1,0,'C');
				$pdf-> Cell(0,8,'Remark',1,1,'C');
				
				$pdf-> setFont('Arial','','9');
					$sql_all_subject= "select * from subject where school_section = '$section_id' and status ='1' ";
					$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
						while($all_subject_array = mysqli_fetch_array($php_process_sql_all_subject)){
							$subject_id = $all_subject_array['id'];
							$subject = $all_subject_array['subject'];
							$subject_code = $all_subject_array['subject_code'];
							$school_section = $all_subject_array['school_section'];	
													
								$check = mysqli_query($conn,"select * from  contineous_accessment where student_info_id = '$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' and subject_id ='$subject_id' and status = '1'");
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
											 
											 
											 //get second term total for the current subject
												$second_term_t_query = mysqli_query($conn,"select * from  contineous_accessment where student_info_id = '$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and term_id = '2' and subject_id ='$subject_id' and status = '1'");
												$second_term_t_rows = mysqli_num_rows($second_term_t_query);
												if($second_term_t_rows > 0){
									
													$second_term_t_arr = mysqli_fetch_array($second_term_t_query);
													$term2 = $second_term_t_arr['total'];
												}
												
												
											 //get first term total for the current subject
											 $first_term_t_query = mysqli_query($conn,"select * from  contineous_accessment where student_info_id = '$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and term_id = '1' and subject_id ='$subject_id' and status = '1'");
												$firs_term_t_rows = mysqli_num_rows($first_term_t_query);
												if($firs_term_t_rows > 0){
									
													$first_term_t_arr = mysqli_fetch_array($first_term_t_query);
													$term1 = $first_term_t_arr['total'];
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
												 
								}else{
											 $ca1 = '-';
											 $ca2 = '-';
											 $ca3 = '-';
											 $total = '-';
											 $exam = '-';
											 $grade = '-';
											 $sub_post = '-';
											 $term1 = '-';
											 $term2 = '-';
											 $term3 = '-';
								}
								 
								$pdf-> Cell(54,8,'  '.strtoupper($subject),1,0,'L');
								$pdf-> Cell(9,8,''.$ca1,1,0,'C');
								$pdf-> Cell(9,8,''.$ca2,1,0,'C');
								$pdf-> Cell(10,8,''.$ca3,1,0,'C');
								$pdf-> Cell(13,8,''.$exam,1,0,'C');
								$pdf-> Cell(16,8,''.$term1,1,0,'C');
								$pdf-> Cell(16,8,''.$term2,1,0,'C');
								$pdf-> Cell(16,8,''.$total,1,0,'C');
								$pdf-> Cell(13,8,''.$grade,1,0,'C');
								$pdf-> Cell(16,8,''.$sub_post,1,0,'C');
								//$pdf-> Cell(0,8,''.$total,1,1,'C');
								if($grade=='A'){
									$pdf-> Cell(0,8,'Excellent',1,1,'C');	
								}
								else if($grade=='B')
								{
									$pdf-> Cell(0,8,'Good',1,1,'C');
								}
								else if($grade=='C')
								{
									$pdf-> Cell(0,8,'Credit',1,1,'C');
								}
								else if($grade=='D')
								{
									$pdf-> Cell(0,8,'Pass',1,1,'C');
								}
								else if($grade=='F')
								{
									$pdf-> setTextColor(225,0,0);
									$pdf-> Cell(0,8,'Fail',1,1,'C');
									$pdf-> setTextColor(27,186,226);
								}else{
									$pdf-> Cell(0,8,'-',1,1,'C');
								}
						}
			}else{
				$pdf-> setFont('Arial','B','9');
				$pdf-> Cell(62,8,'SUBJECTS',1,0,'C');
				$pdf-> Cell(15,8,'CA1',1,0,'C');
				$pdf-> Cell(15,8,'CA2',1,0,'C');
				$pdf-> Cell(15,8,'Test',1,0,'C');
				$pdf-> Cell(16,8,'Exam',1,0,'C');
				$pdf-> Cell(16,8,'Total',1,0,'C');
				$pdf-> Cell(15,8,'Grade',1,0,'C');
				$pdf-> Cell(16,8,'Position',1,0,'C');
				$pdf-> Cell(0,8,'Remark',1,1,'C');
				$pdf-> setFont('Arial','','9');
					$sql_all_subject= "select * from subject where school_section = '$section_id' and status ='1' ";
					$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
						while($all_subject_array = mysqli_fetch_array($php_process_sql_all_subject)){
							$subject_id = $all_subject_array['id'];
							$subject = $all_subject_array['subject'];
							$subject_code = $all_subject_array['subject_code'];
							$school_section = $all_subject_array['school_section'];	
													
								$check = mysqli_query($conn,"select * from  contineous_accessment where student_info_id = '$student_info_id' and class_id = '$class_id' and session_id = '$session_id' and term_id = '$term_id' and subject_id ='$subject_id' and status = '1'");
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
												} 
												//end of get postion for the current subject 
												 
								}else{
											 $ca1 = '-';
											 $ca2 = '-';
											 $ca3 = '-';
											 $total = '-';
											 $exam = '-';
											 $grade = '-';
											 $sub_post = '-';
								}
								 
								$pdf-> Cell(62,8,'  '.strtoupper($subject),1,0,'L');
								$pdf-> Cell(15,8,''.$ca1,1,0,'C');
								$pdf-> Cell(15,8,''.$ca2,1,0,'C');
								$pdf-> Cell(15,8,''.$ca3,1,0,'C');
								$pdf-> Cell(16,8,''.$exam,1,0,'C');
								$pdf-> Cell(16,8,''.$total,1,0,'C');
								if($grade=='F')
								{
									$pdf-> setTextColor(225,0,0); //red color
									$pdf-> Cell(15,8,''.$grade,1,0,'C');
									$pdf-> setTextColor(27,186,226);// back to normal color
								}else{
									$pdf-> Cell(15,8,''.$grade,1,0,'C');
								}
								$pdf-> Cell(16,8,''.$sub_post,1,0,'C');
								//$pdf-> Cell(0,8,'Fail',1,1,'C');
								
								if($grade=='A'){
									$pdf-> Cell(0,8,'Excellent',1,1,'C');	
								}
								else if($grade=='B')
								{
									$pdf-> Cell(0,8,'Good',1,1,'C');
								}
								else if($grade=='C')
								{
									$pdf-> Cell(0,8,'Credit',1,1,'C');
								}
								else if($grade=='D')
								{
									$pdf-> Cell(0,8,'Pass',1,1,'C');
								}
								else if($grade=='F')
								{
									$pdf-> setTextColor(225,0,0);
									$pdf-> Cell(0,8,'Fail',1,1,'C');
									$pdf-> setTextColor(27,186,226);
								}else{
									$pdf-> Cell(0,8,'-',1,1,'C');
								}
						}
			}
			
				
													
									
					
						
					
					mysqli_close($conn);
					
		
				$pdf->Ln(10);
		$pdf-> Cell(100,8,'___________________________________',0,0,'C');
		$pdf-> Cell(0,8,'_____________________________________',0,1,'C');
		$pdf-> Cell(100,8,'Class Teacher\'s Sign & Date',0,0,'C');
		$pdf-> Cell(0,8,'Examiner\'s Sign & Date',0,1,'C');
				
				
	
	$pdf->Output();
	}else{
		
	}
?>