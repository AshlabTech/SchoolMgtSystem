<?php 
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
	require_once('../../php/fpdf181/fpdf.php');
	session_start();
	include_once('../../php/connection.php');
		 $class_id= mysqli_real_escape_string($conn,$_GET['cl']);
		 $subject_id= mysqli_real_escape_string($conn,$_GET['sub']);
		 $term_id= mysqli_real_escape_string($conn,$_GET['tr']);
		
		$remark = 0;
		$last_num = 0;
		$nth = '';
		
		
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
				
				
					
				
	// Create instance of the class_alias
	$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));
	$pdf-> setTitle('pss');
	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf  -> AddPage('P','A4',360);
	
	//heading 
$pdf->Image('../../../images/'.$school_image,166 ,20,25,25);
	$pdf-> setTextColor(0,0,0);
	$pdf-> setFont('Arial','B','26');
	$pdf-> Cell(0,10,$school_name,0,1,'C');
	$pdf-> setFont('Arial','B','14');
	$pdf-> Cell(0,8,$school_address,0,1,'C');
	$pdf-> setTextColor(209,20,29);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,'CONTINUOUS ASSESSMENT REPORT BOOK ',0,1,'C');
	$pdf-> setTextColor(0,0,0);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,''.$subject,0,1,'C');
	
	$pdf->Ln(5);
	$pdf-> setTextColor(0,0,0);
	$pdf->SetLineWidth(0.3);
			$pdf->SetDrawColor(0,0,0);
			$pdf->Line(32,51,88,51);
			$pdf->Line(105,51,140,51);
			$pdf->Line(157,51,198,51);
			
		$pdf-> setFont('Arial','','9');
		$pdf-> Cell(20,8,'Class:',0,0,'C');
		$pdf-> Cell(55,8,'',0,0,'L');
		$pdf-> Cell(20,8,'Term:',0,0,'C');
		$pdf-> Cell(35,8,'',0,0,'L');
		
		$pdf-> Cell(20,8,'Code:',0,0,'C');
		$pdf-> Cell(0,8,'',0,1,'L');
		$pdf->Ln(5);
		
				$pdf-> Cell(10,8,'SN',1,0,'C');
				$pdf-> Cell(65,8,'Name',1,0,'C');
				$pdf-> Cell(10,8,'Sex',1,0,'C');
				$pdf-> Cell(10,8,'CA1',1,0,'C');
				$pdf-> Cell(10,8,'CA2',1,0,'C');
				$pdf-> Cell(10,8,'Test',1,0,'C');
				$pdf-> Cell(20,8,'Exam',1,0,'C');
				$pdf-> Cell(20,8,'Total',1,0,'C');
				$pdf-> Cell(20,8,'Grade',1,0,'C');
				$pdf-> Cell(0,8,'Remark',1,1,'C');
				
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
											
								$pdf-> Cell(10,8,''.$sn,1,0,'C');
								$pdf-> Cell(65,8,$full_name,1,0,'L');
								$pdf-> Cell(10,8,$gender,1,0,'C');
								$pdf-> Cell(10,8,$ca1,1,0,'C');
								$pdf-> Cell(10,8,$ca2,1,0,'C');
								$pdf-> Cell(10,8,$ca3,1,0,'C');
								$pdf-> Cell(20,8,$exam,1,0,'C');
								$pdf-> Cell(20,8,$total,1,0,'C');
								$pdf-> Cell(20,8,$grade,1,0,'C');
								$pdf-> Cell(0,8,$sub_post,1,1,'C');
								
									$sn++;
						}
						
					}
								
									
					
						
					
					mysqli_close($conn);
					
		
				$pdf->Ln(10);
		$pdf-> Cell(100,8,'___________________________________',0,0,'C');
		$pdf-> Cell(0,8,'_____________________________________',0,1,'C');
		$pdf-> Cell(100,8,'Class Teacher\'s Sign & Date',0,0,'C');
		$pdf-> Cell(0,8,'Examiner\'s Sign & Date',0,1,'C');
				
	$pdf->Output("D");
?>