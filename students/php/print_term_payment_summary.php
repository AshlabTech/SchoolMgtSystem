<?php 
require_once('fpdf181/fpdf.php');
	session_start();
	include_once('connection.php');
?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
	}
	else{
		header('location:../');
	}

	
	
		// Create instance of the class_alias
	$pdf = new FPDF();
	//var_dump(get_class_methods($pdf));
	$pdf-> setTitle('pss');
	//$pdf  -> AddPage('paper orientation 1.e 1. P or Portrait 2. L or Landscape','size :A3,A4,A5,Letter', rotation: multiple of 90);
	$pdf  -> AddPage('P','A4',360);
	
		//get current session id
			$get_current_session_id_query = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
			$session_id = $get_current_session_id_arr['section_id'];
			$current_session = $get_current_session_id_arr['section'];
	
	
		// Get the current term
			$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
			$term_array = mysqli_fetch_array($term);
			$term = $term_array['term'];
			$term_id = $term_array['id'];
			$term_full = $term_array['description'];
			
				// realises this term		
					$total_for_term_sql = "select SUM(amount_paid) from school_fees where  ";
					$total_for_term_sql .= "session_id = '$session_id' and term_id = '$term' and  (status = '1' or status = '2')";
					$total_for_term_query =  mysqli_query($conn,$total_for_term_sql) or die(mysqli_error($conn));
					$total_for_term_row = mysqli_fetch_row($total_for_term_query);		
					$total_for_term = $total_for_term_row[0];
					
						$sn = 1;
	//heading 
	$pdf-> setTextColor(27,186,226);
	$pdf-> setFont('Arial','B','26');
	//$pdf->Image('../images/pss_logo.png',20 ,20,20,20);
	$pdf-> Cell(0,10,'PEACE GROUP OF SCHOOLS',0,1,'C');
	$pdf-> setFont('Arial','B','14');
	$pdf-> Cell(0,8,'P.O.Box 135,Rabba Road Mokwa Niger State',0,1,'C');
	$pdf-> setTextColor(209,20,29);
	$pdf-> setFont('Arial','B','12');
	$pdf-> Cell(0,6,strtoupper($term_full).' '.$current_session.' ACADEMIC SESSION\'S PAYMENT SUMMARY FOR ALL THE MONTHS',0,1,'C');
	$pdf->Ln(10);
						$pdf-> setFont('Arial','B','9');
						$pdf-> setTextColor(0,0,0);
										$pdf-> Cell(10,8,'SN',1,0,'C');
										$pdf-> Cell(150,8,'Month',1,0,'C');
										$pdf-> Cell(0,8,'Total Amount ',1,1,'C');
										
										
					$all_months = mysqli_query($conn,"select * from months") or die(mysqli_error($conn));
					while($rows = mysqli_fetch_array($all_months)){
						$month_id  = $rows['month_id'];
						$month_full  = $rows['month_full'];
						
						$total_for_term_Monthly_sql = "select SUM(amount_paid) from school_fees where month = '$month_id' and ";
						$total_for_term_Monthly_sql .= "session_id = '$session_id' and term_id = '$term' and  (status = '1' or status = '2')";
						$total_for_term_Monthly_query =  mysqli_query($conn,$total_for_term_Monthly_sql) or die(mysqli_error($conn));
						$total_for_term_Monthly_row = mysqli_fetch_row($total_for_term_Monthly_query);	
						$total_for_month = $total_for_term_Monthly_row[0];
							if($total_for_month==''){
								$total_for_month=0;
							}
												
										$pdf-> Cell(10,8,''.$sn++,1,0,'C');
										$pdf-> Cell(150,8,'		'.$month_full,1,0,'L');
										$pdf-> Cell(0,8,' N'.$total_for_month,1,1,'C');
					}
										$pdf-> Cell(10,8,'',1,0,'C');
										$pdf-> Cell(150,8,'		TOTAL = ',1,0,'L');
										$pdf-> Cell(0,8,' N'.$total_for_term,1,1,'C');
					
$pdf->Output();
		
?>