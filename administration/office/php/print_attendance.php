<?php session_start(); ?>
<?php 
include_once('../../php/connection.php');
require_once('../../php/fpdf181/fpdf.php');
		//if(isset($_GET['cl']) and isset($_GET['excel_m'])){
						//$cl_id = $_GET['cl']; // class id
						//$att_month = $_GET['excel_m'];
						$output_table = "";
						$cl_id = $_SESSION['cl'];
						$att_month = $_SESSION['excel_m'];
						$session_id = $_SESSION['session_id'];
						$current_year = $_SESSION['current_year'];
						
												$sql_class = "select * from classes where class_id = '$cl_id' and status = '1'";
												$sql_class_run =  mysqli_query($conn,$sql_class) or die(mysqli_error($conn));
													$class_row = mysqli_fetch_assoc($sql_class_run);
													$class_name = $class_row['class_name'];
												
							$get_months2 = mysqli_query($conn,"select * from months where month_id = '$att_month'");
							$number_of_days_array = mysqli_fetch_assoc($get_months2); 
							$month_abr = $number_of_days_array['month_abr'];
							$month_full = $number_of_days_array['month_full'];
							$number_of_days = $number_of_days_array['number_of_days'];
							
							$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));
	$pdf-> setTitle('pss');
	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf  -> AddPage('L','A4',360);
	
	$pdf->Image('../../images/logo_pss.png',220 ,7,20,20);
	$pdf->Image('../../images/logo_pss.png',55 ,7,20,20);
	$pdf-> setTextColor(27,186,226);
	$pdf-> setFont('Arial','B','26');
	$pdf-> Cell(0,10,'PEACE SECONDARY SCHOOL',0,1,'C');
	$pdf-> setFont('Arial','B','14');
	$pdf-> Cell(0,8,'P.O.Box 135,Rabba Road Mokwa Niger State',0,1,'C');
	$pdf-> setTextColor(209,20,29);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,'ATTENDANCE SHEET OF '.$class_name.' FOR THE MONTH OF '.strtoupper($month_full).' , YEAR '.$current_year,0,1,'C');
	$pdf-> setTextColor(0,0,0);
		$pdf->Ln(8);
		$pdf-> setFont('Arial','','10');
		//$pdf->SetLineWidth(0.3);
			$pdf->SetDrawColor(0,0,0);
	
				
											
											$sql_all_class = "select * from student_classes where class_id = '$cl_id' and status = '1' ORDER BY student_class_id ";
											$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
												$num_rows_all_class = mysqli_num_rows($query_all_class);
												if($num_rows_all_class > 0){
													
															$sn = 1;
														
													
													while($array_all_class = mysqli_fetch_array($query_all_class)){
														$student_info_id = $array_all_class['student_info_id'];
														$sql_student_info = "select * from student_info where student_info_id = '$student_info_id' ";
															
															$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
															$num_rows_student_info = mysqli_num_rows($query_student_info);		
																
																if($num_rows_student_info > 0){
																	
																		$arrray_student_info = mysqli_fetch_array($query_student_info);
																		$first_name = $arrray_student_info['first_name'];
																		$last_name = $arrray_student_info['last_name'];
																		$other_name = $arrray_student_info['other_name'];
																		$gender = $arrray_student_info['gender'];
																		$image_name = $arrray_student_info['image_name'];
																		$full_name = $first_name.' '.$other_name.' '.$last_name;
																		
																		
																				$pdf-> Cell(10,8,''.$sn,1,0,'C');
																				$pdf-> Cell(65,8,''.$full_name,1,0,'L');
																		
																					
																						for($day_in_month = 1;$day_in_month <= $number_of_days;$day_in_month++){
																							 
																							 $att_query = mysqli_query($conn,"select * from student_attendance where student_info_id = '$student_info_id' and session_id = '$session_id' and class_id = '$cl_id' and  year = '$current_year' and month = '$att_month' and day = '$day_in_month'  ") or die(mysqli_error($conn));
																								$att_query_num_rows = mysqli_num_rows($att_query);
																								if($att_query_num_rows > 0){
																									$att_rows = mysqli_fetch_array($att_query);
																										
																										$status = $att_rows['status'];
																										 if($status == 1){
																												if($day_in_month==($number_of_days)){
																													 $pdf-> Cell(6,8,'1',1,1,'C');
																												}else{
																													 $pdf-> Cell(6,8,'1',1,0,'C');
																												}
																											
																										 }
																										else
																										{
																											if($day_in_month==($number_of_days)){
																													 $pdf-> Cell(6,8,'0',1,1,'C');
																												}else{
																													 $pdf-> Cell(6,8,'0',1,0,'C');
																												}
																										}
																									
																																									
																								}else{
																										if($day_in_month==($number_of_days)){
																													 $pdf-> Cell(6,8,'0',1,1,'C');
																												}else{
																													 $pdf-> Cell(6,8,'0',1,0,'C');
																												}
																								}
																							
																							
																						}
																				
																			
																		$sn++;
																}
													}
												}
								
		$pdf->Output();
?>