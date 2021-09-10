<?php
session_start();

include_once('connection.php');
include_once('student_data.php');
$student_info_id = $_GET['token'];
$class_id = $_GET['cl'];
$term_id = $_GET['tr'];


//$student_infodd = mysqli_query($conn, "SELECT * FROM student_info as st  INNER JOIN student_classes as s ON s.student_info_id = st.student_info_id 
	// WHERE st.student_info_id = '$student_info_id'  AND s.class_id= '$class_id'");

	$student_infodd = mysqli_query($conn, "SELECT * FROM student_info WHERE student_info_id='$student_info_id'");
$student = mysqli_fetch_assoc($student_infodd);
$first_name = $student['first_name'];
$other_name = $student['other_name'];
$last_name = $student['last_name'];
$admission_no = $student['adm_no'];
$name = $first_name.' '.$other_name.' '.$last_name;

$query2 =  mysqli_query($conn, "select * from student_classes where student_info_id = '$student_info_id' and session_id = '$session_id'  and class_id ='$class_id'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_assoc($query2);
    $school_fees = $row2['school_fees'];

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
				$tm = '1st';
			}
			elseif($term_id == 2){
				$tm = '2nd';
			}
			else if($term_id == 3){
				$tm = '3rd';
			}
			
			$sn = 1;
			$tt = 0;
			$feeInfo = [];
			$total_amount_paid =0;
			$ballance =0;
			while($term_payment_rows = mysqli_fetch_array($term_payments_query)){
						$status = $term_payment_rows['status'];
						$year = $term_payment_rows['year'];
						$month = $term_payment_rows['month'];
						$day = $term_payment_rows['day'];
						$mydate = $day.'-'.$month.'-'.$year;
						$payment_number = $term_payment_rows['payment_number'];
						$amount_paid = $term_payment_rows['amount_paid'];
						array_push($feeInfo, $mydate );
						array_push($feeInfo, $amount_paid );
						$ballance = $term_payment_rows['ballance'];
						$payment_madeBy = $term_payment_rows['payment_madeBy'];
						$date = $day.' - '.$month.' - '.$year;
						$tt = $tt + $amount_paid;
						$total_amount_paid += $amount_paid;
					
			}

			$total_amount_paid = $total_amount_paid + $ballance;
			$ballance_remaining = $ballance;
				if($status == '2'){
						$image_type = 'full_payment.png';
					}else{
						$image_type = 'part_payment.png';
					}
						
		}else{
			//echo $class_id;
			//exit();
		}
					
	

//echo $dataPDF;

/* Send headers
*/
$filename = $_POST['pInfo1n']."_school_fee_receipt.pdf";

$header = [
	$school_name,
	$school_address
];

	$image = '../../images/'.$school_image;
//echo $image;
$sname = $student['first_name'].' '.$student['other_name']. ' '.$student['last_name'];
$admNo = $student['adm_no'];
$session = $year;
$term = $tm;
$className = $class_name;
$ballance = $student['school_fees'] - $tt;
$details = [
	    $total_amount_paid,
		$tt,
		$ballance_remaining
	];
   //include_once('../../assets/fpdf/setasign/fpdf/rotation.php');
   include_once('../../assets/fpdf/setasign/fpdf/rotation.php');



	class PDF extends PDF_Rotate
	{

    

		function FancyTable($header, $sn,$ad,$se,$tm,$cl,$fi,$de,$image1)
		{

					$this->Image($image1,90,18,45,45);
					//$this->Image('../img/userlogo.jpg',50,148,90,90);
					$this->SetTextColor(25,10,139);
					$this->SetFont('Times','B', 12);
					$this->cell(166,95,$header[0],0,1,'C');

					$this->SetTextColor(0,0,0);
					$this->setY(85);
					$this->SetFillColor(255,255,255);
					$this->SetFont('Arial','', 8.5);
					$this->Multicell(170,9,$header[1],0,'C');
					
					$this->SetTextColor(250,0,9);
					$this->SetFont('Times','B', 11);
					$this->cell(166,25,'School Fee Reciept',0,1, 'C');

					//$this->SetLeftMargin(10);
					$this->SetTextColor(0,0,0);
					$this->setX(10);
					$this->SetFont('Arial','B', 8.5);
					$this->cell(42,35,'Name:',0,0);
					$this->SetFont('Arial','');
					$this->cell(84,35,$sn,0,0);
					
					$this->SetFont('Arial','B', 8.5);
					$this->cell(30,35,'Term:',0,0);
					$this->SetFont('Arial','');
					$this->cell(150,35,ucwords($tm),0,1);
					//end line

					$this->setY(116);
					$this->setX(10);
					$this->SetFont('Arial','B');
					$this->cell(42,37,'Adm No:',0,0);
					$this->SetFont('Arial','');
					$this->cell(84,37,$ad,0,0);

					$this->SetFont('Arial','B', 8.5);
					$this->cell(30,37,'Class:',0,0);
					$this->SetFont('Arial','');
					$this->cell(150,37,ucwords($cl),0,1);
					
					$this->setY(147);
					$this->setX(10);
					$this->SetTextColor(250,0,9);
					$this->SetFont('Arial','B', 10);
					$this->cell(166,38,'Fee Information',0,1);

					$this->setY(175);
					$this->setX(12);
					$this->SetFillColor(00,200,200);
					$this->SetTextColor(40,40,40);
					$this->SetFont('Arial','B',8.5);
					$this->cell(98,13,'Date',1,0);
					$this->cell(98,13,'Amount Paid',1,1);

					$this->SetFillColor(255,255,255);				
					$this->SetTextColor(40,40,40);
					$this->SetFont('Arial','B',8.5);
					for ($i=0; $i < sizeof($fi); $i+=2) { 
						$this->setX(12);
						$this->SetFont('Arial');
						$this->cell(98,13, $fi[$i],1,0);
						$this->cell(98,13, number_format($fi[$i+1],2,'.',','),1,1);					
					}
					$this->Ln(10);
					$this->setX(10);
					$this->SetDrawColor(0,0,0);
					$this->Line(10,0,100,0);
					$this->SetFont('Arial','B',8.5);
					$this->cell(50,10,'Total Fee:',0,0);
					$this->SetFont('Arial','');
					$this->cell(50,10, $de[0],0,1);

					$this->setX(10);
					$this->SetFont('Arial','B',8.5);
					$this->cell(50,10,'Total Paid:',0,0);
					$this->SetFont('Arial','');
					$this->cell(50,10,$de[1],0,1);
					
					$this->setX(10);
					$this->SetFont('Arial','B',8.5);
					$this->cell(50,10,'Balance:',0,0);
					$this->SetFont('Arial','');
					$this->cell(50,10,$de[2],0,1);
					



		}
		//$this->Cell(array_sum($w),0,'','T');
	}
	//[218.268, 311.811]

	$pdf = new PDF('p', 'pt',[222.215,341.811] );
	// Column headings
	
	// Data loading
	
	$pdf->SetFont('Arial','',14);
	$pdf->AddPage();

	$pdf->FancyTable($header,$name,$admission_no,$year,$tm,$class_name,$feeInfo,$details, $image);
	$pdf->Output('I',$filename);

?>