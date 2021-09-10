<?php 
			
session_start();
include_once('../php/connection.php');

$image = '../../../images/'.$school_image;
$data = $_POST['data'];
$class = $_POST['class'];
$subject = $_POST['subject'];
$term = $_POST['term'];
$session = $_POST['session'];
$subject_section = $_POST['section'];
//var_dump($av);
$get_set_score =$conn->query("SELECT * FROM score WHERE section_id = '$subject_section' AND activate='1'");
if ($get_set_score->num_rows>0) {
	$scorep = $get_set_score->fetch_assoc();
	$ca1Data = $scorep['ca1'];
	$ca2Data = $scorep['ca2'];
	$ca3Data = $scorep['ca3'];
	$examData = $scorep['exam'];
}else{
	$ca1Data = 0;
	$ca2Data = 0;
	$ca3Data = 0;
	$examData = 0;
}
	
include_once('../../../assets/fpdf/setasign/fpdf/rotation.php');
//echo "dstring";

	class dash extends PDF_Rotate{
		function SetDash($black=null,$white=null){
			if($black!==null){
				$s=sprintf('[%.3F %.3F] 0 d', $black*$this->k,$white*$this->k);
			}else{
				$s = '[] 0 d';
				$this->_out($s);
			}
		}
	}

	class PDF extends dash
	{
		function myCell($w, $h, $x, $t,$s,$f){
			$height =$h/3;
			$first  = $height+2;
			$second = $height+$height+$height+3;
			$third = $height+$height+$height+9;
			$len = strlen($t);
			if ($len>$s) {
				$txt = str_split($t,$s);
				$this->SetX($x);
				$this->Cell($w,$first,$txt[0],'',$f,'');
				$this->SetX($x);
				$this->Cell($w,$second,$txt[1],'',$f,'');
				$this->SetX($x);
				$this->Cell($w,$third,@$txt[2],'',$f,'');
				$this->SetX($x);
				$this->Cell($w,$h,'','LTRB',0,'C',0);
			}else{
				$this->SetX($x);
				$this->Cell($w,$h,$t,'LTRB',0,'C',0);
			}
		}
	function FancyTable($s,$a,$se,$tm, $class, $sub, $image1)
	{


				$this->Image($image1,15,15,18,18);
				//$this->Image('../img/userlogo.jpg',50,148,90,90);
				///$this->SetTextColor(25,10,139);
					//$j=0;
				
				$this->SetFont('Arial','', 15);
				$this->cell(200,10,$s,0,1,'C');
				$this->SetFont('Arial','', 9);
				$this->cell(200,22,$a,0,1,'C');
				$this->SetFont('Arial','', 11);
				$this->Multicell(200,-10,"CONTINOUS ACCESSMENT SHEET FOR ".strtoupper($tm).", ".$se." ACADEMIC SESSION
",0,'C');
				$this->SetTextColor(0,0,0);
				$this->cell(50,20,"Class:",0,0,"R");
				$this->SetTextColor(50,110,200);	
				$this->cell(30,20,$class,0,0,"L");
				//break

				//start
				$this->SetTextColor(0,0,0);
				$this->cell(20,20,"Subject:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(520,20,$sub,0,1,"LR");
				//break
//-----------------------------------------------------------------//



			}
				function LoadData($data)
				{
					$dataR = array();
					$dataR = explode(';',rtrim($data,';'));
					return $dataR;
				}
			
		//$this->Cell(array_sum($w),0,'','T');
	}
	//[218.268, 311.811]

	$pdf = new PDF();
	// Column headings
	
	// Data loading
	
	$pdf->AddPage();
	$pdf->Rect(5,5,200,287,'D');
	$data = $pdf->LoadData($data);
	$pdf->FancyTable($school_name,$school_address,$session,$term,$class,$subject, $image);

	//$pdf->tableResult($data);
	$sumT = intval($ca1Data) + intval($ca2Data) + intval($ca3Data);
	$w = array(40,17,17,17,17,17,17,17,16,25);
				$pdf->setY(55);
				$pdf->setX(5);
				$pdf->SetFont('Arial','B',8.5);
				$pdf->SetTextColor(0,0,0);
				$h =12;
				$x = $pdf->Getx();
				$pdf->myCell($w[9], $h,$x, 'Adm No.',9,0);
				$x = $pdf->Getx();
				$pdf->myCell($w[0], $h,$x, 'Name',9,0);
				//$pdf->Ln(0);
				$x = $pdf->Getx();
				$pdf->myCell($w[1], $h,$x,  'CA 1 '.$ca1Data.'%',4,0);$x = $pdf->Getx();
				$pdf->myCell($w[2], $h,$x,  'CA 2 '.$ca2Data.'%',4,0);$x = $pdf->Getx();
				$pdf->myCell($w[3], $h,$x,  'CA 3 '.$ca3Data.'%',4,0);$x = $pdf->Getx();
				$pdf->myCell($w[4], $h,$x,  'TOTAL '. $sumT.'%',5,0);$x = $pdf->Getx();
				$pdf->myCell($w[5], $h,$x,  'EXAM '.$examData.'%',5,0);$x = $pdf->Getx();
				$pdf->myCell($w[6], $h,$x,  'TOTAL 100%',5,0);$x = $pdf->Getx();
				$pdf->myCell($w[7], $h,$x,  'GRD',5,0);$x = $pdf->Getx();
				$pdf->myCell($w[8], $h,$x,  'POS',5,1);$x = $pdf->Getx();			
				//$pdf->myCell($w[11], $h,$x,  'CLASS AVE',5,0);$x = $pdf->Getx();
				//$pdf->myCell($w[12], $h,$x,  'COMMENT',8,1);
				$pdf->Ln();
				$pdf->SetFont('Arial','', 8);
	$count=0;
for ($i=0; $i < sizeof($data) ; $i+=9) { 
	$count++;
	$pdf->setX(5);
	
	$ht =6;
		if ($i == 0) {
			
			$pdf->Cell($w[9],$ht,ucwords($data[0]),1,0,'LR');


			$pdf->Cell($w[0],$ht,$data[1],1,0,'L');
			
			if ($data[2] < ($ca1Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$data[2],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$data[2],1,0,'C');				
			}

			if ($data[3] < ($ca2Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$data[3],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$data[3],1,0,'C');				
			}

			if ($data[4] < ($ca3Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$data[4],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$data[4],1,0,'C');				
			}


		
			$pdf->SetTextColor(0,0,0);
			$ttm = $data[2]+$data[3]+$data[4];
			//total in overall

			if ($ttm < ($sumT/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[4],$ht,$ttm,1,0,'C');
				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[4],$ht,$ttm,1,0,'C');
			}

			if ($data[5] < ($examData/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[4],$ht,$data[5],1,0,'C');
				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[4],$ht,$data[5],1,0,'C');
			}

			
			if(intval($data[6])>59){
				$pdf->SetTextColor(50,100,0);				
				$pdf->Cell($w[6],$ht,$data[6],1,0,'C');
			}elseif(intval($data[5])<60 && intval($data[5])>49){
				$pdf->SetTextColor(50,0,100);
				
				$pdf->Cell($w[6],$ht,$data[6],1,0,'C');
			}else{
				$pdf->SetTextColor(200,0,0);				
				$pdf->Cell($w[6],$ht,$data[6],1,0,'C');
			}	

			$pdf->SetTextColor(0,0,0);
			$pdf->Cell($w[7],$ht,$data[7],1,0,'C');		
			$pdf->Cell($w[8],$ht,ucwords($data[8]),1,1 ,'LR');			
		}else{
			$pdf->Cell($w[9],$ht,ucwords($data[0]),1,0,'LR');


			$pdf->Cell($w[0],$ht,$data[$i+1],1,0,'L');
			
			if ($data[$i+2] < ($ca1Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$data[$i+2],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$data[$i+2],1,0,'C');				
			}

			if ($data[$i+3] < ($ca2Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$data[$i+3],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$data[$i+3],1,0,'C');				
			}

			if ($data[$i+4] < ($ca3Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$data[$i+4],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$data[$i+4],1,0,'C');				
			}


		
			$pdf->SetTextColor(0,0,0);
			$ttm = $data[$i+2]+$data[$i+3]+$data[$i+4];
			//total in overall

			if ($ttm < ($sumT/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[4],$ht,$ttm,1,0,'C');
				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[4],$ht,$ttm,1,0,'C');
			}

			if ($data[$i+5] < ($examData/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[4],$ht,$data[$i+5],1,0,'C');
				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[4],$ht,$data[$i+5],1,0,'C');
			}

			
			if(intval($data[$i+6])>59){
				$pdf->SetTextColor(50,100,0);				
				$pdf->Cell($w[6],$ht,$data[$i+6],1,0,'C');
			}elseif(intval($data[$i+6])<60 && intval($data[$i+6])>49){
				$pdf->SetTextColor(50,0,100);				
				$pdf->Cell($w[6],$ht,$data[$i+6],1,0,'C');
			}else{
				$pdf->SetTextColor(200,0,0);				
				$pdf->Cell($w[6],$ht,$data[$i+6],1,0,'C');
			}	

			$pdf->SetTextColor(0,0,0);
			$pdf->Cell($w[7],$ht,$data[$i+7],1,0,'C');		
			$pdf->Cell($w[8],$ht,ucwords($data[$i+8]),1,1 ,'LR');	
			
		}
	}
$pdf->Output('D',$subject.'.pdf');

