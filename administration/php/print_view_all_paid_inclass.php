<?php 

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once('fpdf181/fpdf.php');
	session_start();
	include_once('connection.php');
	
?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
			if(isset($_GET['tok'])){
				$class_id_hash = $_GET['tok'];
			}
			else{
				exit();
			}
	}
	else{
		header('location:../');
	}
	

	$h1;
	$term;
	$status;
	$term_id;
	$class_id;
	$slip_title;
	$term_array;
	$session_id;
	$sql_prev_amount;
	$sum_prev_amount;
	$school_fees_sql;
	$query_prev_amount;
	$total_school_fees;
	$total_amount_paid;
	$school_fees_arr;
	$school_fees_query;
	$remaining_ballance;
	$distinct_students_sql;
	$distinct_students_rows;
	$distinct_students_query;
	$get_current_session_id_arr;
	$get_current_session_id_query;
	
	
	// Create instance of the class_alias
	$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));
	$pdf-> setTitle($school_abbr);
	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf  -> AddPage('P','A4',360);
	
	
	if(isset($_GET['term'])){
	      $session_id = $_GET['se'];
			$get_current_session_id_query = mysqli_query($conn,"select * from session where section_id = '$session_id' ") or die(mysqli_error($conn));
			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
			$session_id = $get_current_session_id_arr['section_id'];
			$current_session = $get_current_session_id_arr['section'];
			
		$term_id = $_GET['term'];
			$term = mysqli_query($conn,"select * from term  where id='$term_id' ORDER BY status DESC") or die(mysqli_error($conn));
			$term_array = mysqli_fetch_array($term);
			$term = $term_array['term'];
			$term_id = $term_array['id'];
			$term_full = $term_array['description'];
			
	}else{
	    	//get current session id
			$get_current_session_id_query = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
			$session_id = $get_current_session_id_arr['section_id'];
	
	
		// Get the current term
			$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
			$term_array = mysqli_fetch_array($term);
			$term = $term_array['term'];
			$term_id = $term_array['id'];
	}
				
		// Get class name
				$query_student_section=  mysqli_query($conn,"select * from classes where  status = '1'") or die(mysqli_error($conn));
							$num_rows_sql_student_section = mysqli_num_rows($query_student_section);		
									while($class_section = mysqli_fetch_array($query_student_section)){
										$class_idd = $class_section['class_id'];
											if(md5($class_idd) == $class_id_hash ){
												$class_id = $class_idd;
												
												$school_section_id = $class_section['school_section_id'];
												$class_description = $class_section['description'];
												$class_name = $class_section['class_name'];
											}
										
									}
									
								$slip_title = $term_full.' '.$current_session.' - '.$class_name.' Payment Summary Slip';
								if($school_section_id < 3){
									$h1 = $school_name;
								}else{
									$h1 = $school_name;
								}
									
				
						$sn = 1;
	//heading 
	$pdf-> setTextColor(27,186,226);
	$pdf-> setFont('Arial','B','26');
    $pdf->Image('../../images/'.$school_image,20 ,20,20,20);
	$pdf-> Cell(0,10,$h1,0,1,'C');
	$pdf-> setFont('Arial','B','14');
	$pdf-> Cell(0,8,$school_address,0,1,'C');
	$pdf-> setTextColor(209,20,29);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,$slip_title,0,1,'C');
	$pdf->Ln(10);
						$pdf-> setFont('Arial','B','9');
						$pdf-> setTextColor(0,0,0);
										$pdf-> Cell(10,8,'SN',1,0,'C');
										$pdf-> Cell(90,8,'Name',1,0,'C');
										$pdf-> Cell(40,8,'Total Amount paid',1,0,'C');
										$pdf-> Cell(0,8,'Remaining Ballance',1,1,'C');
										$pdf-> setFont('Arial','','9');
									//
									
									$all_student_in_class = "select * from student_classes as s inner join student_info as st on st.student_info_id= s.student_info_id where s.session_id = '$session_id' and s.term_id = '$term' and s.class_id='$class_id' and (s.status = '1'  or s.status ='2') ";
				$run_student_in_class_query =  $conn->query($all_student_in_class) or die(mysqli_error($conn));
				if ($run_student_in_class_query->num_rows > 0) {
					while ($student = $run_student_in_class_query->fetch_assoc()) {
						$student_info_id = $student['student_info_id'];
						$full_name = $student['first_name'].' '.$student['other_name'].' '.$student['last_name'];							
						$total_amount_paid = '0.00';	
						$total_school_fees = $student['school_fees'];			
						$distinct_students_sql = "select  * from school_fees where student_info_id='$student_info_id' and session_id = '$session_id' and term_id = '$term' and class_id ='$class_id' ";
							$distinct_students_sql .= "and (status = '1'  or status ='2') ";
							$distinct_students_query =  mysqli_query($conn,$distinct_students_sql) or die(mysqli_error($conn));
							$distinct_students_rows =  mysqli_num_rows($distinct_students_query);
							$half_paid = 0;
							if($distinct_students_rows > 0){						
								while($rows = mysqli_fetch_array($distinct_students_query)){
								/*	$student_info_id = $rows['student_info_id'];*/
										
										// get amount the student has paid for the session , class, and term 		
											$sql_prev_amount = "select SUM(amount_paid) from school_fees where student_info_id = '$student_info_id' and ";
											$sql_prev_amount .= "session_id = '$session_id' and term_id = '$term' and class_id ='$class_id' and (status = '1' or status = '2')";
											$query_prev_amount =  mysqli_query($conn,$sql_prev_amount) or die(mysqli_error($conn));
											$sum_prev_amount = mysqli_fetch_row($query_prev_amount);		
											$total_amount_paid = $sum_prev_amount[0];
											
											$remaining_ballance = $total_school_fees  - $total_amount_paid;
											if($remaining_ballance <0){
												$remaining_ballance =0;
											}
											
											if(($total_amount_paid == $total_school_fees) or $remaining_ballance <=0){
												$status = "<b style='color:green'> Full Payment </b>";
											}
											else{
												$status = "<b style='color:blue'> Half Payment </b>";
												
											}
										/*	include('../php/student_data.php');*/
													
								}
							
								$pdf-> Cell(10,8,$sn++,1,0,'C');
								$pdf-> Cell(90,8,$full_name,1,0,'L');
								$pdf-> Cell(40,8,'N'.$total_amount_paid,1,0,'C');
								$pdf-> Cell(0,8,'N'.$remaining_ballance,1,1,'C');
							}else{
										
														$pdf-> Cell(10,8,$sn++,1,0,'C');
														$pdf-> Cell(90,8,$full_name,1,0,'L');
														$pdf-> Cell(40,8,'N'.$total_amount_paid,1,0,'C');
														$pdf-> Cell(0,8,'N'.$total_school_fees,1,1,'C');
							}
					}
				}

										
											
		$pdf->Output();
?>

