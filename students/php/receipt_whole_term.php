<?php
session_start();

if(isset($_GET['token']) and isset($_GET['cl']) and isset($_GET['tr'])){
			
			$student_info_id = $_GET['token'];
			
		   require_once('fpdf181/fpdf.php');
			include_once('connection.php');
			include_once('student_data.php');
			$class_id = $_GET['cl'];
			$term_id = $_GET['tr'];
		//get class name
				$distinct_className_sql = "select * from classes where class_id = '$class_id' and status = '1'";
				$distinct_className_query=  mysqli_query($conn,$distinct_className_sql) or die(mysqli_error($conn));
				$distinct_className_rows = mysqli_num_rows($distinct_className_query);		
				if($distinct_className_rows > 0){
					$class_name_array= mysqli_fetch_array($distinct_className_query);
					$school_section_id = $class_name_array['school_section_id'];
					$class_description = $class_name_array['description'];
					$class_name = $class_name_array['class_name'];
				}
					
									
									
										$term_payments_sql = "select * from school_fees where student_info_id = '$student_info_id' and class_id = '$class_id' and term_id = '$term_id'";
										$term_payments_query =  mysqli_query($conn,$term_payments_sql) or die(mysqli_error($conn));
										$term_payments_query_rows =  mysqli_num_rows($term_payments_query);
											if($term_payments_query_rows > 0){
												if($term_id == 1){
													$tm = 'First (1st) Term';
												}
												elseif($term_id == 2){
													$tm = 'Second (2nd) Term';
												}
												else if($term_id == 3){
													$tm = 'Third (3rd) Term';
												}
												
												$sn = 1;
												$tt = 0;
												while($term_payment_rows = mysqli_fetch_array($term_payments_query)){
															$status = $term_payment_rows['status'];
															$year = $term_payment_rows['year'];
															$month = $term_payment_rows['month'];
															$day = $term_payment_rows['day'];
															$payment_number = $term_payment_rows['payment_number'];
															$amount_paid = $term_payment_rows['amount_paid'];
															$ballance = $term_payment_rows['ballance'];
															$payment_madeBy = $term_payment_rows['payment_madeBy'];
															$date = $day.' - '.$month.' - '.$year;
															$tt = $tt + $amount_paid;
															
														
												}
													if($status == '2'){
															$image_type = 'full_payment.png';
														}else{
															$image_type = 'part_payment.png';
														}
															
											}else{
												echo $class_id;
												exit();
											}
					
	// Create instance of the class_alias
	$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));

	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf  -> AddPage('P','A4',360);
		
			$pdf->SetDrawColor(0,0,0);

	$pdf->SetLineWidth(0.3);
	
	$pdf->Rect(13,10,180,95,'D');
	$pdf->Ln(4);
	
	$pdf->Image('../images/'.$image_type,13 ,10,180,70);
	$pdf-> setTextColor(47,186,226);
	$pdf-> setFont('Arial','B','10');
	
	$pdf->Ln(28);
	
	$pdf-> cell(45,5,'',0,0,'L');
	$pdf-> cell(100,5,' ',0,0,'C');
	$pdf-> setTextColor(0,0,0);
	$pdf-> setFont('Courier','B','12');
	$pdf-> cell(0,5,'NO.: 9040940',0,1,'L');
	
		$pdf->SetDrawColor(47,186,226);
	$pdf->Line(32,52,189,52);
	

	$pdf-> setTextColor(47,186,226);
	$pdf-> setFont('Arial','','12');
	
	//name row
	$pdf-> cell(20,5,'        Name :',0,0,'C');
	$pdf-> cell(0,5,'    '.$full_name,0,1,'L');
	
	$pdf->Ln(5);
	//class date and term row
	$pdf-> cell(30,5,' Class :',0,0,'C');
	$pdf->Line(32,62,78,62);
	$pdf-> cell(30,5,''.$class_name,0,0,'L');
	
	$pdf-> cell(20,5,'            Date :',0,0,'C');
	$pdf->Line(95,62,130,62);
	$pdf-> cell(30,5,'       '.$date,0,0,'C');
	
	$pdf-> cell(20,5,'           Term :',0,0,'C');
		$pdf->Line(145,62,190,62);
	$pdf-> cell(0,5,'    '.$tm,0,1,'L');
	//end class date and term row
	$pdf->Ln(5);
	//Amount paid row
	$pdf-> cell(30,5,'              Amount Paid :',0,0,'C');
	$pdf-> cell(0,5,'     ____________________________________________(N  '.$tt.' : 00 K)',0,1,'L');
	$pdf->Ln(5);
	//Ballance row
	$pdf-> cell(30,5,'        Balance :',0,0,'C');
	$pdf-> cell(0,5,' ____________________________________________(N   '.$ballance.' : 00 K)',0,1,'L');
	//end of ballance row
	$pdf->Ln(5);
	//SIgn row
	$pdf-> cell(60,5,'             ______________________',0,0,'C');
	$pdf-> cell(0,5,'    _______________________           ',0,1,'R');
	//end of signatories row
	
	$pdf-> setFont('Arial','B','8');
	//SIgn row
	$pdf-> cell(60,5,'             Payer\'s Sign/Date',0,0,'C');
	$pdf-> cell(43,5,'             ',0,0,'C');
	$pdf-> cell(0,5,'    Receiver\'s Sign/Date   ',0,1,'C');
	//end of signatories row
	
	
	//Kepp save row

	$pdf-> cell(0,5,'    N:B Keep this receipt as evidence of payment   ',0,0,'C');

	
	
	$pdf->PageNo(1)	;
	
	$pdf->Output();
}else
{
			exit();
}
	

?>