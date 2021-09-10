<?php 
			
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
$heads ="";
// $hash_student_info_id= mysqli_real_escape_string($conn,$_GET['token']);
 $class_id= $_POST['class_id'];
 $session_id= $_POST['session_id'];
 $term_id		= $_POST['term_id'];
 $allData = array();
 $allTotalScores = array();
 $numOfSubject =0;

$remarkD = '';
$remark = array("A"=>"Excellent","B"=>"V.Good","C"=>"Good","D"=>"Pass","E"=>"poor","F"=>"V. Poor");


//calculating score averages  in class
$f_av = $conn->query("SELECT *, r.subject_id as sub FROM contineous_accessment as r WHERE r.term_id='$term_id' AND r.session_id='$session_id' AND r.class_id='$class_id'");
$arr_subj = array();
$arr1_avg = array();
if($f_av->num_rows >0){
	$c = 0;
	while ($rwf = $f_av->fetch_assoc()) {
		if ($c==0) {
			$arr_subj[] = $rwf['sub'];	
			$c++;
		}else{
			if($arr_subj[$c-1]==$rwf['sub']){
			}else{
				$arr_subj[] = $rwf['sub'];	
				$c++;
			}
		}
	}	
}

		//end score
$getTermConfig = $conn->query("SELECT * FROM term_config as t WHERE t.session ='$session_id' AND t.value = '1' LIMIT 1");
if ($getTermConfig->num_rows>0) {
	$termConfig = true;
}else{
	$termConfig = false;
}

$get_set_score =$conn->query("SELECT * FROM classes as c INNER JOIN score as s ON s.section_id=c.school_section_id WHERE c.class_id= '$class_id' AND activate='1'");
$scorep = $get_set_score->fetch_assoc();
$ca1Data = $scorep['ca1'];
$ca2Data = $scorep['ca2'];
$ca3Data = $scorep['ca3'];
$examData = $scorep['exam'];


$getAllStudent = $conn->query("SELECT * FROM student_classes as c INNER JOIN student_info as s ON s.student_info_id=c.student_info_id INNER JOIN classes as cl ON cl.class_id=c.class_id INNER JOIN session AS se ON se.section_id=c.session_id INNER JOIN term as t ON t.id=c.term_id WHERE c.session_id='$session_id' AND c.term_id='$term_id' AND c.class_id='$class_id'")or die(mysqli_error($conn));

if ($getAllStudent->num_rows> 0) {

	while ($student = $getAllStudent->fetch_assoc()) {
		$student_info_id = $student['student_info_id'];
		$id = $student_info_id;
		$addNo = $student['adm_no'];
		$full_name = $student['first_name'].' '.$student['other_name'].' '.$student['last_name'] ;
		$className = $student['class_name'];
		$term = $student['description'];
		$session = $student['section'];
		$num_in_class = $geAlltStudentData->num_rows;
		

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

		$total_present = $total_marked_attendace;
		$total_absent = $total_absent_inschool;
		$subjectsAndScores = array();
		$subjectValue = array();
		$geAlltStudentData = $conn->query("SELECT * FROM contineous_accessment as c INNER JOIN subject as sb ON sb.id=c.subject_id WHERE c.session_id='$session_id' AND c.term_id='$term_id' AND c.class_id='$class_id' AND c.student_info_id='$student_info_id' ORDER BY sb.subject");
		if ($geAlltStudentData->num_rows> 0) {
			while($row = $geAlltStudentData->fetch_assoc()) {
				$numOfSubject++;
				$subject = $row['subject'];
				$subject_id = $row['subject_id'];



			if ($term_id != 3) {
				//geting avg low and high in subject
				$fff = $conn->query("SELECT *, subject as sub, min(r.total) as min, max(r.total) as max FROM contineous_accessment as r INNER JOIN subject as s ON s.id=r.subject_id WHERE r.subject_id='$subject_id' AND r.term_id='$term_id' AND r.session_id='$session_id' AND r.class_id='$class_id'")or die(mysqli_error($conn));		
				$ff = $fff->fetch_assoc();
				$cgrade =$row['grade'];
				$ctotal =$row['total'];
				$cexam =$row['exam'];
			}else{
				if ($termConfig == true) {
					//geting avg low and high in subject
					$fff = $conn->query("SELECT *, subject as sub, min(r.total) as min, max(r.total) as max FROM contineous_accessment as r INNER JOIN subject as s ON s.id=r.subject_id WHERE r.subject_id='$subject_id' AND r.term_id='$term_id' AND r.session_id='$session_id' AND r.class_id='$class_id'")or die(mysqli_error($conn));		
					$ff = $fff->fetch_assoc();
					$cgrade =$row['grade'];
					$ctotal =$row['total'];
					$cexam =$row['exam'];	
				}else{
					//geting avg low and high in subject
					$fff = $conn->query("SELECT *, subject as sub, min(r.total3) as min, max(r.total3) as max FROM contineous_accessment as r INNER JOIN subject as s ON s.id=r.subject_id WHERE r.subject_id='$subject_id' AND r.term_id='$term_id' AND r.session_id='$session_id' AND r.class_id='$class_id'")or die(mysqli_error($conn));		
					$ff = $fff->fetch_assoc();
					$cgrade =$row['grade3'];
					$ctotal =$row['total3'];				
					$cexam =$row['exam3'];

				}

			}	
			//class subject average
			$av1=$av2=$av3='';
			$avg = number_format((float)(($ff['max']+$ff['min'])/2),1,'.','');
			$avg1 = number_format($ff['min'],1,'.','');
			$avg2 = number_format($ff['max'],1,'.','');

				foreach ($remark as $key => $value) {
						if ($cgrade==$key) {
						//	echo $value;
							$remarkD = $value;
						}										
					}

				
				if ($ca1Data != 0 and $ca2Data != 0 and $ca3Data != 0) {
					$summT = $row['ca1'] + $row['ca2']+ $row['ca3'];
					$summTT =$ca1Data + $ca1Data + $ca3Data;
					$subjectsAndScores[$subject]=[
						'a+CA-1-'.$ca1Data.'%' => $row['ca1'],
						'b+CA-2-'.$ca2Data.'%' => $row['ca2'],
						'c+CA-3-'.$ca3Data.'%' =>  $row['ca3'],
						'd+total '.$summTT.'%' =>  $summT,
						'e+exam-'.$examData.'%'=>  $cexam,
						'f+total 100%'		=>  $ctotal,
						'g+grd'    			=>  $cgrade,
						'h+pos'				=>	$row['position'],
						'i+low--  in -class'=>  $avg1,
						'j+high-  in-class'=>  $avg2,
						'k+class-ave'			=>  $avg,
						'l+comment'			=>  $remarkD
					];		
					$subjectValue[$subject]=[ 
						$row['ca1'], 
						$row['ca2'],
						$row['ca3'],
						$summT,
						$cexam,
						$ctotal,
						$cgrade,
						$row['position'],
						$avg1,
						$avg2,
						$avg,
						$remarkD
					];				
				}
			}
		}
//echo var_dump($subjectsAndScores);

		//term result 
		$sqlTerm = $conn->query("SELECT * FROM term_result WHERE student_info_id='$student_info_id' AND session_id='$session_id' AND term_id='$term_id' AND class_id='$class_id'");
		$rowTerm = $sqlTerm->fetch_assoc();
		if ($term_id != 3) {
			$allTotalScores[] = $rowTerm['total'];
			$totalScore = $rowTerm['total'];
			$OverAllposition = $rowTerm['position'];
			$OverAllgrade = $rowTerm['grade'];
		}else{
			if ($termConfig == true) {
				$allTotalScores[] = $rowTerm['total'];
				$totalScore = $rowTerm['total'];
				$OverAllposition = $rowTerm['position'];
				$OverAllgrade = $rowTerm['grade'];	
			}else{
				$allTotalScores[] = $rowTerm['total3'];
				$OverAllposition = $rowTerm['position3'];
				$totalScore = $rowTerm['total3'];
				$OverAllgrade = $rowTerm['grade3'];							
			}

		}	

		$allData[$id] =[
					'admNo' => $addNo,
					'full_name'=>$full_name,
					'class_name'=>$className,
					'term'=>$term,
					'session'=>$session,
					'num_in_class'=>$num_in_class,
					'present'=>$total_present,
					'absent'=>$absent,
					'position'=> $OverAllposition,
					'total'=> $totalScore,					
					'subjects'=> $subjectsAndScores,
					'allsubjects'=>$subjectValue,
					'no-of-subject:'=> $numOfSubject,
					'finalAverage'=> $totalScore/$geAlltStudentData->num_rows


				];

	}
$numOfSubject = $geAlltStudentData->num_rows;
}else{
	//no student in class
}			
$num_in_class = $getAllStudent->num_rows;
$formTeacher = mysqli_query($conn, "SELECT * FROM `staff_classes` AS c INNER JOIN staff_info as s ON s.staff_info_id = c.staff_info_id  WHERE c.class_id='$class_id' AND c.session_id = '$session_id' ");
$ff = mysqli_num_rows($formTeacher);
	if($ff>0){
		$clb = mysqli_fetch_array($formTeacher);
		$formTeacher = $clb['title'].'. '.$clb['first_name'].' '.$clb['other_name'].' '.$clb['last_name'];				
	}else{
		$formTeacher ="";
	}
// DETERMINE STUDENT SECTION

	$className = ucwords($class_name);
	if(strpos( $className, 'NUR')){
		$ss = 'NURSERY PUPILS';
	}
	else if(strpos( $className, 'PRI')){
			$ss = 'PRIMARY PUPILS';
	}
	else if(strpos( $className, 'JUNIOR') or strpos( $className, 'J.S.S') or strpos( $className, 'JSS')){
		$ss = 'JUNIOR SECONDARY SCHOOL (J.S.S)';
	}
	else if(strpos( $className, 'SECONDARY') or strpos( $className, 'SECON') or strpos( $className, 'SSS')){
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
$sectionalTerm  = mysqli_query($conn,"SELECT * FROM `sectional_term` WHERE  session_id = '$session_id' and term_id = '$term_id'");

$sectionDate = mysqli_num_rows($sectionalTerm);
	if($sectionDate>0){
		$cc = mysqli_fetch_array($sectionDate);
		$open = $cc['school_open_count'];
		$resumption = $cc['resumption_date'];

	}else{

	}
$get_grade = $conn->query("SELECT * FROM grade LIMIT 1");
$grade_r = $get_grade->fetch_assoc();

$headName = $heads;

$class_avge = array_sum($allTotalScores)/sizeof($allTotalScores);
$class_average = number_format($class_avge,1,'.','');
//echo var_dump($Data);
//fetch low and high

//traits config
$empty0= 0;
	$empty1= 0;

	$psycc = $conn->query("SELECT *  FROM psycomotor");
	if($psycc->num_rows>0){
		$e_sr = array();
		while ($py= $psycc->fetch_assoc()) {
			$p_sr[] = $py['id'];
			//echo $py['id'].'<br>';
		}
		$p_s = sizeof($p_sr);

	}else{
		$empty0 = 1;
		/*
		?>
	<script>alert("error:Line :385");</script>
		<?php*/
	}
	$effc = $conn->query("SELECT *  FROM affective_domain");
	if($effc->num_rows>0){
		while ($ec = $effc->fetch_assoc()) {
			$e_sr[] = $ec['id'];
		}
		$e_s = sizeof($e_sr);
	}else{
		$empty1 = 1;
		/*?>
		<script>//alert("error:Line :395");</script>
		<?php*/
	}
//end trait config
$image = '../../images/'.$school_image;
//var_dump($av);
include_once('../../assets/fpdf/setasign/fpdf/rotation.php');
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
	function FancyTable($s,$a,$se,$tm,$name, $class, $num_in_class,$pos,$addNo,$total,$open,$present,$absent,$session,$term,$resumption,$class_avge,$image1,$finalAverage)
	{


				$this->Image($image1,15,15,18,18);
				//$this->Image('../img/userlogo.jpg',50,148,90,90);
				///$this->SetTextColor(25,10,139);
					//$j=0;
				$schl_opens = $present + $absent;
					$cn =0;
			
				$sumT = number_format($finalAverage,2,'.',''); 

				$this->SetFont('Arial','', 15);
				$this->cell(200,10,$s,0,1,'C');
				$this->SetFont('Arial','', 9);
				$this->cell(200,22,$a,0,1,'C');
				$this->SetFont('Arial','', 11);
				$this->Multicell(200,-10,"REPORT SHEET FOR ".strtoupper($tm).", ".$se." ACADEMIC SESSION
",0,'C');
				$this->Line(5, 40, 205, 40);
				$this->setX(5);
				//start
				$this->cell(35,23,"NAME:",0,0,"L");	
				$this->SetTextColor(50,110,200);
				$this->cell(60,23,strtoupper($name),0,1,"LR");
				//break
				//start
				$this->setX(5);
				$this->SetTextColor(0,0,0);
				$this->cell(35,-10,"Class:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(40,-10,ucfirst(strtoupper($class)),0,0,"LR");
				//break
				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,-10,"Final Position:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,-10,ucfirst($pos),0,1,"LR");
				//break

				//ATTENDANT
				$this->SetTextColor(0,0,0);
				$this->cell(160,24,'ATTENDANCE',0,1,'R');
//-----------------------------------------------------------------//
				$this->setX(5);
				$this->SetTextColor(0,0,0);
				$this->cell(35,-10,"Admission No:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(40,-10,ucfirst(strtolower($addNo)),0,0,"LR");
				//break
				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,-10,"Total Score:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,-10,ucfirst($total),0,0,"LR");
				//break

				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,-10,"Times School Open:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,-10,$schl_opens,0,1,"LR");
				//break
//-----------------------------------------------------------------//

//-----------------------------------------------------------------//
				$this->setX(5);
				$this->SetTextColor(0,0,0);
				$this->cell(35,20,"Session",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(40,20,ucfirst(strtolower($session)),0,0,"LR");
				//break
				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,20,"Final Average:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,20,$sumT,0,0,"LR");
				//break

				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,20,"Times Present:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,20,ucfirst($present),0,1,"LR");
				//break
//-----------------------------------------------------------------//
//-----------------------------------------------------------------//
				$this->setX(5);
				$this->SetTextColor(0,0,0);
				$this->cell(35,-10,"Term",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(40,-10,ucwords(strtolower($term)),0,0,"LR");
				//break
				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,-10,"Class Average:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,-10,$class_avge ,0,0,"LR");
				//break

				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,-10,"Times Absent:",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,-10,ucfirst($absent),0,1,"LR");
				//break
//-----------------------------------------------------------------//
//-----------------------------------------------------------------//
				$this->setX(5);
				$this->SetTextColor(0,0,0);
				$this->cell(35,20,"No in Class",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(40,20,$num_in_class,0,0,"LR");
				//break
				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,20,"",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,20,'',0,0,"LR");
				//break

				//start
				$this->SetTextColor(0,0,0);
				$this->cell(40,20,"Resumption Date",0,0,"L");
				$this->SetTextColor(50,110,200);	
				$this->cell(20,20,date("M. d, Y",strtotime($resumption)),0,1,"LR");
				//break
//-----------------------------------------------------------------//



			}
				/*function LoadData($data)
				{
					$dataR = array();
					$dataR = explode(';',rtrim($data,';'));
					return $dataR;
				}*/
			
		//$this->Cell(array_sum($w),0,'','T');
	}
	//[218.268, 311.811]

	$pdf = new PDF();
	// Column headings

	// Data loading
	
	//$data = $pdf->LoadData($Data);
foreach ($allData as $student_id => $data) {
	$pdf->AddPage();
	$pdf->Rect(5,5,200,287,'D');
	$pdf->FancyTable($school_name,$school_address,$data['session'],$data['term'],$data['full_name'],$data['class_name'],$num_in_class,$data['position'],$data['admNo'],$data['total'],$open,$data['present'],$data['absent'],$data['session'],$data['term'],$resumption,$class_average,$image,$data['finalAverage']);
	$countUP = 0;
	foreach ($data['subjects'] as $subject => $value) {
		if ($countUP == 0) {
			$countA = sizeof($value);

			if ($countA == 12) {
				$w = array(36,12,12,12,15,12,15,12,12,12,12,12,26);
				$pdf->setY(85);
				$pdf->setX(5);
				$pdf->SetFont('Arial','B',8.5);
				$pdf->SetTextColor(0,0,0);
				$h =12;
				$x = $pdf->Getx();
				$pdf->myCell($w[0], $h,$x, 'SUBJECT',9,0);
				//$pdf->Ln(0);
				$x = $pdf->Getx();
				$n = 1;
				//creating header
				foreach ($value as $key => $headT) {
					$keyU = explode('+',$key );
					$key = $keyU[1];
					if ($n == 12) {
						$pdf->myCell($w[12], $h,$x,  'COMMENT',8,1);
					}else if($n==11){
						$pdf->myCell($w[11], $h,$x,  'CLASS AVE',5,0);$x = $pdf->Getx();						
					}else{
						$pdf->myCell($w[$n], $h,$x, strtoupper(str_replace('-', ' ', trim($key))),5,0);$x = $pdf->Getx();						
					}
					$n++;
				}			
			}else if($countA == 11){
				$w = array(42,12,12,14,12,14,12,12,12,12,12,34);
				$pdf->setY(85);
				$pdf->setX(5);
				$pdf->SetFont('Arial','B',8.5);
				$pdf->SetTextColor(0,0,0);
				$h =12;
				$x = $pdf->Getx();
				$pdf->myCell($w[0], $h,$x, 'SUBJECT',9,0);
				//$pdf->Ln(0);
				$x = $pdf->Getx();
				$n = 1;
				//creating header
				foreach ($value as $key => $headT) {
					$keyU = explode('+',$key );
					$key = $keyU[1];
					if ($n == 11) {
						$pdf->myCell($w[11], $h,$x,  'COMMENT',8,1);
					}else if($n==10){
						$pdf->myCell($w[10], $h,$x,  'CLASS AVE',5,0);$x = $pdf->Getx();						
					}else{
						$pdf->myCell($w[$n], $h,$x, strtoupper(str_replace('-', ' ', $key)),5,1);$x = $pdf->Getx();						
					}
					$n++;
				}		

			}else if($countA == 10){
				$w = array(45,13,13,13,13,13,13,13,13,13,38);
				$pdf->setY(85);
				$pdf->setX(5);
				$pdf->SetFont('Arial','B',8.5);
				$pdf->SetTextColor(0,0,0);
				$h =12;
				$x = $pdf->Getx();
				$pdf->myCell($w[0], $h,$x, 'SUBJECT',9,0);
				//$pdf->Ln(0);
				$x = $pdf->Getx();
				$n = 1;
				//creating header
				foreach ($value as $key => $headT) {
					$keyU = explode('+',$key );
					$key = $keyU[1];
					if ($n == 11) {
						$pdf->myCell($w[11], $h,$x,  'COMMENT',8,1);
					}else if($n==10){
						$pdf->myCell($w[10], $h,$x,  'CLASS AVE',5,0);$x = $pdf->Getx();						
					}else{
						$pdf->myCell($w[$n], $h,$x, strtoupper(str_replace('-', ' ', $key)),5,1);$x = $pdf->Getx();						
					}
					$n++;
				}		
			}
			$pdf->Ln();
			# code...
		}else{
			break;
		}

	}

	$pdf->SetFont('Arial','', 8);
	$ht =6;
	foreach ($data['allsubjects'] as $subject => $innData) {
			$pdf->setX(5);
			
			$pdf->Cell($w[0],$ht,ucwords($subject),1,0,'LR');		

			if ($innData[0] < ($ca1Data/2)) {
				$pdf->SetTextColor(200,0,0);
				$pdf->Cell($w[1],$ht,$innData[0],1,0,'C');				
			}else{
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[1],$ht,$innData[0],1,0,'C');								
			}

			if ($ca2Data !=0 ) {
				if ($innData[1] < ($ca2Data/2)) {
					$pdf->SetTextColor(200,0,0);
					$pdf->Cell($w[2],$ht,$innData[1],1,0,'C');				
				}else{
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell($w[2],$ht,$innData[1],1,0,'C');								
				}
			}

			if ($ca3Data !=0 ) {
				if ($innData[2] < ($ca3Data/2)) {
					$pdf->SetTextColor(200,0,0);
					$pdf->Cell($w[3],$ht,$innData[2],1,0,'C');				
				}else{
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell($w[3],$ht,$innData[2],1,0,'C');								
				}
				//data after CAs
				$sumT = $ca1Data + $ca2Data +$ca3Data;			
				if ($innData[3] < ($sumT/2)) {
					$pdf->SetTextColor(200,0,0);
					$pdf->Cell($w[4],$ht,$innData[3],1,0,'C');
					
				}else{
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell($w[4],$ht,$innData[3],1,0,'C');
				}

				

				if ($innData[4] < ($examData/2)) {
					$pdf->SetTextColor(200,0,0);
					$pdf->Cell($w[5],$ht,$innData[4],1,0,'C');
					
				}else{
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell($w[5],$ht,$innData[4],1,0,'C');
				}


				if(intval($innData[5])>59){
					$pdf->SetTextColor(50,100,0);				
					$pdf->Cell($w[6],$ht,$innData[5],1,0,'C');
				}elseif(intval($innData[5])<60 && intval($innData[5])>49){
					$pdf->SetTextColor(50,0,100);				
					$pdf->Cell($w[6],$ht,$innData[5],1,0,'C');
				}else{
					$pdf->SetTextColor(200,0,0);				
					$pdf->Cell($w[6],$ht,$innData[5],1,0,'C');
				}	
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[7],$ht,$innData[6],1,0,'C');
				$pdf->Cell($w[8],$ht,$innData[7],1,0,'C');
				$pdf->Cell($w[9],$ht,$innData[8],1,0,'C');
				$pdf->Cell($w[10],$ht,$innData[9],1,0,'C');
				$pdf->Cell($w[11],$ht,$innData[10],1,0,'C');
				$pdf->Cell($w[12],$ht,$innData[11],1,1,'LR');
			}else{
				//data after CAs
				$sumT = $ca1Data + $ca2Data;
				if ($innData[2] < ($sumT/2)) {
					$pdf->SetTextColor(200,0,0);
					$pdf->Cell($w[3],$ht,$innData[2],1,0,'C');
					
				}else{
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell($w[3],$ht,$innData[2],1,0,'C');
				}

				

				if ($innData[3] < ($examData/2)) {
					$pdf->SetTextColor(200,0,0);
					$pdf->Cell($w[4],$ht,$innData[3],1,0,'C');
					
				}else{
					$pdf->SetTextColor(0,0,0);
					$pdf->Cell($w[4],$ht,$innData[3],1,0,'C');
				}


				if(intval($innData[4])>59){
					$pdf->SetTextColor(50,100,0);				
					$pdf->Cell($w[5],$ht,$innData[4],1,0,'C');
				}elseif(intval($innData[4])<60 && intval($innData[4])>49){
					$pdf->SetTextColor(50,0,100);				
					$pdf->Cell($w[5],$ht,$innData[4],1,0,'C');
				}else{
					$pdf->SetTextColor(200,0,0);				
					$pdf->Cell($w[5],$ht,$innData[4],1,0,'C');
				}	
				$pdf->SetTextColor(0,0,0);
				$pdf->Cell($w[6],$ht,$innData[5],1,0,'C');
				$pdf->Cell($w[7],$ht,$innData[6],1,0,'C');
				$pdf->Cell($w[8],$ht,$innData[7],1,0,'C');
				$pdf->Cell($w[9],$ht,$innData[8],1,0,'C');
				$pdf->Cell($w[10],$ht,$innData[9],1,0,'C');
				$pdf->Cell($w[11],$ht,$innData[10],1,1,'LR');
			}
			
			
	}

	$pdf->SetX(5);
	$pdf->SetTextColor(50,110,200);
	$pdf->SetFont('Arial','', 11);
	$pdf->Cell(160,6,'Keys to Grading:',0,0,'L');
	$pdf->Cell(40,6,'No. of subject: '.$numOfSubject,0,1,'L');
	$pdf->SetX(5);
	
	$pdf->Cell(150,6,'A = '.$grade_r['A'].'-100, B= '.$grade_r['B'].'-'.($grade_r['A']-1).', C= '.($grade_r['B']-1).'-'.$grade_r['C'].', D= '.($grade_r['C']-1).'-'.$grade_r['D'].', E= '.($grade_r['D']-1).'-'.$grade_r['E'].', F= '.($grade_r['E']-1).'-'.$grade_r['F'],0,1,'L');
	$pdf->SetX(5);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','', 8);
	//traits
	$pdf->cell(70,4,'PSYCOMOTOR',1,0,'L');
	$pdf->cell(20,4,'RATING',1,0,'L');
	$pdf->cell(20,4,'',0,0,'L');
	$pdf->cell(70,4,'AFFECTIVE DOMAIN',1,0,'L');
	$pdf->cell(20,4,'RATING',1,1,'L');
	
	

	$pyid = $eid = "";
	if($empty1==0 && $empty0==0){
		if($e_s>$p_s || $e_s==$p_s ){
			for ($i=0; $i < $e_s; $i++) { 
				$eid = $e_sr[$i];
				if($i>$p_s){$pyid='';}else{$pyid = $p_sr[$i];}
			
				$traits0 = $conn->query("SELECT * FROM student_a_traits as e INNER JOIN affective_domain as a ON a.id='$eid' WHERE e.student_id='$student_id' AND e.affective_domain_id='$eid' AND e.term_id='$term_id' AND e.session_id='$session_id'");
				$aff = $traits0->fetch_assoc();
				$anm0 = $aff['a_name'];
				$rnm0 = $aff['rate'];
				$traits1 = $conn->query("SELECT * FROM student_p_traits as ps INNER JOIN psycomotor as pt ON pt.id='$pyid' WHERE ps.student_id='$student_id' AND ps.psycomotor_id='$pyid' AND ps.term_id='$term_id' AND ps.session_id='$session_id'");
				$anm1 = 0;
				$rnm1 = 0;
				//echo mysqli_error($conn);

				if($traits1->num_rows>0){
					$psy = $traits1->fetch_assoc();
					$anm1 = $psy['p_name'];
					$rnm1 = $psy['rate'];
				}
					
				$pdf->SetX(5);
				if($anm1 ==0 AND $rnm1==0){
					$pdf->cell(70,4,'',1,0,'L');
					$pdf->cell(20,4,'',1,0,'L');
				}else{
					$pdf->cell(70,4,$anm1,1,0,'L');
					$pdf->cell(20,4,$rnm1,1,0,'L');
				}
				$pdf->cell(20,4,'',0,0,'L');
				$pdf->cell(70,4,$anm0,1,0,'L');
				$pdf->cell(20,4,$rnm0,1,1,'L');
			}
		}else{
			for ($i=0; $i <$p_s; $i++) { 
				$pyid = $p_sr[$i];
				if($i>$e_s){$eid='';}else{$eid = $e_sr[$i];}
				$pdf->setX(5);
				$traits0 = $conn->query("SELECT * FROM student_a_traits as e INNER JOIN affective_domain as a ON a.id='$eid' WHERE e.student_id='$student_id' AND e.affective_domain_id='$eid' AND e.term_id='$term_id' AND e.session_id='$session_id'");
				$aff = $traits0->fetch_assoc();
				$anm1 = 0;
				$rnm1 = 0;
				if($traits0->num_rows>0){
					$aff = $traits0->fetch_assoc();
					$anm1 = $aff['a_name'];
					$rnm1 = $aff['rate'];
				}
				$traits1 = $conn->query("SELECT * FROM student_p_traits as ps INNER JOIN psycomotor as p ON p.id='$pyid' WHERE ps.student_id='$student_id' AND ps.psycomotor_id='$pyid' AND ps.term_id='$term_id' AND ps.session_id='$session_id'");
					$psy = $traits1->fetch_assoc();
					$anm0 = $psy['p_name'];
					$rnm0 = $psy['rate'];

				if($anm1 ==0 AND $rnm1==0){
					$pdf->cell(70,4,'',1,0,'L');
					$pdf->cell(20,4,'',1,0,'L');
				}else{
					$pdf->cell(70,4,$anm1,1,0,'L');
					$pdf->cell(20,4,$rnm1,1,0,'L');
				}
				$pdf->cell(20,4,'',0,0,'L');
				$pdf->cell(70,4,$anm0,1,0,'L');
				$pdf->cell(20,4,$rnm0,1,1,'L');
			}
		}
	}
	/*End traits*/
$pdf->Ln(1);
//comments----------------------------------------------------------
	//$pdf->Line(5, 160, 205, 160);
	$st = 142;
	$height = $st + ($count*6);
	/*form master start*/
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	
	if($deficiency_count>0){
		$def =1;
	}else{
		$def =0;
	}
/*	$comment = $conn->query("SELECT * FROM comments WHERE deficiency='$def' AND result_grade='$overallS_grade' AND type= 3");
	$fc = $comment->fetch_assoc();*/
	$pdf->cell(45,6,'FORM TEACHER COMMENT: ',0,0,'L');
	//$pdf->cell(40,6,ucfirst($fc['comments']),0,1,'L');
	$pdf->cell(40,6,'',0,1,'L');
	$pdf->setX(5);
	$pdf->cell(45,6,'FORM TEACHER NAME: ',0,0,'L');
	$pdf->cell(40,6,$formTeacher,0,1,'L');
	//$pdf->Image('../formTeacher/sign/'.$formTeacherId.'.jpg',98,$height,15,8);
	/*end*/
	if ($headgender=='Male') {
		$headI = 'HEADMASTER';
	}else{
		$headI = 'HEADMISTRESS';
	}
	$pdf->setX(5);
	$pdf->SetFont('Arial','',9);
	/*$comment = $conn->query("SELECT * FROM comments WHERE deficiency='$def' AND result_grade='$overallS_grade' AND type=2");
	$fc = $comment->fetch_assoc();*/
	$pdf->cell(45,6,$headI.' COMMENT: ',0,0,'L');
	//$pdf->cell(40,6,ucfirst($fc['comments']),0,1,'L');
	$pdf->cell(40,6,'',0,1,'L');
	$pdf->setX(5);
	$pdf->cell(45,6,$headI.' NAME: ',0,0,'L');
	$pdf->cell(40,6,$headName,0,1,'L');

	//dont show signature if result not approved
	if (file_exists('../hs/sign/'.$headid.'.jpg')) {
		if($approvedR!=0){
			$pdf->Image('../hs/sign/'.$headid.'.jpg',20,261,15,8);
		}
	}
	if (file_exists('../formTeacher/sign/'.$formTeacherId.'.jpg')) {
		$pdf->Image('../formTeacher/sign/'.$formTeacherId.'.jpg',170,261,15,8);
	}
	/*end*/
//-----------------------------------------------------------------//
	//$overallS_grade;
	//$deficiency_count;

	$pdf->setX(10);
	$pdf->setY(275);
	$pdf->setLineWidth(0.1);
	$pdf->SetDash(5,5);
	$pdf->Line(10,269,50,269);
	$pdf->setY(272);
	$pdf->cell(40,4,'FORM TEACHER SIGN: ',0,0,'C');
	$pdf->cell(140,4,'',0,0,'C');

	$pdf->setX(150);
	$pdf->setY(275);
	$pdf->setLineWidth(0.1);
	$pdf->SetDash(5,5);
	$pdf->Line(160,269,200,269);
	$pdf->setY(272);
	$pdf->setX(150);
	$pdf->cell(60,4,$headI.' SIGN',0,0,'C');
}

	//$pdf->tableResult($data);


	$pdf->Output('I',$filename);

