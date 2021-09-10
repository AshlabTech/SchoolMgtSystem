<?php
include_once('../../php/connection.php');
session_start();
$data = $_POST['data'];
//echo var_dump($data);
$teacher_id = $_SESSION['staff_info_id'];
$tid = $_POST['tid'];
$term_id = $_POST['tid'];
$sid = $_POST['sid'];
$session_id = $_POST['sid'];
$bid = $_POST['bid'];
$class_id = $_POST['bid'];
$sbid = $_POST['sbid'];
$subject_id = $_POST['sbid'];
	//$add_st_to_result = $conn->query("INSERT INTO `result`(`id`, `student_info_id`, `subject_id`, `class_id`, `term_id`, `session_id`, `ca1`, `ca2`,ca3, `exam`, `total`, `subject_teacher_id`) VALUES (null, '$stid','$sbid','$bid','$tid','$sid','','','','','0','$teacher_id')");
$deficiency = 0;

	$get_grade = $conn->query("SELECT * FROM grade LIMIT 1");
	$grade_r = $get_grade->fetch_assoc();
	function gradeSys($a,$b1,$b2,$b3,$b4,$b5,$b6)
	{
		switch (true) {
			case ($a>=$b1):
				return 'A';
				break;
			case ($a>=$b2):
				return 'B';
				break;
			case ($a>=$b3):
				return 'C';
				break;
			case ($a>=$b4):
				return 'D';
				break;
			case ($a>=$b5):
				return 'E';
				break;
			case ($a>=$b6):
				return 'F';
				break;
		}
	}
	$ct =0;
	$cn =0;
function ordinal($i) {
  	return $i.(($j=abs($i)%100)>10&&$j<14?'th':@['th','st','nd','rd'][$j%10]?:'th');
}
	array_multisort(array_column($data, 'total'), SORT_DESC, $data);
	$l = 1;
	$data1 = array();
foreach ($data as $key) {
		$stid = $key['id'];
		$ca1 = $key['ca1'];
		$ca2 = $key['ca2'];
		$ca3 = $key['ca3'];
		$exam = $key['exam'];
		$total = $key['total'];
//check and create student in the result table if not created
	$sqlchk = $conn->query("SELECT * FROM contineous_accessment WHERE student_info_id='$stid' AND subject_id='$sbid' AND class_id='$bid' AND session_id='$sid' AND term_id='$tid'");
if ($sqlchk->num_rows>0) {
	//echo 0;
}else{

	$add_st_to_result = $conn->query("INSERT INTO `contineous_accessment`(`id`, `student_info_id`, `subject_id`, `class_id`, `term_id`, `session_id`, `ca1`, `ca2`,ca3, `exam`, `total`, `subject_teacher_id`) VALUES (null, '$stid','$sbid','$bid','$tid','$sid','','','','','0','$teacher_id')");
	if (!$add_st_to_result) {
		echo 0;
		echo mysqli_error($conn);
	}else{
		echo 1;
	}
}

$sqlchk1 = $conn->query("SELECT * FROM term_result WHERE student_info_id='$stid'  AND class_id='$bid' AND session_id='$sid' AND term_id='$tid'");
if ($sqlchk1->num_rows>0) {
	//echo 0;
}else{

	$add_st_to_result1 = $conn->query("INSERT INTO `term_result`(`id`, `student_info_id`, `session_id`, `term_id`, `class_id`, `total`, `position`) VALUES (null, '$stid','$sid','$tid','$bid','0','')");
	if (!$add_st_to_result1) {
		echo 0;
		echo mysqli_error($conn);
	}else{
		echo 1;
	}
}
//end create student

		if($l==1){
			$pos = ordinal($l);
			$lastT = $total;
		}else{
			if($lastT==$total){
				$l-=1;
				$pos = ordinal($l);
				$lastT = $total;
			}else{
				$pos = ordinal($l);
				$lastT = $total;
			}
		}
		$l++;
		//echo $total;
//save subject scores
	$grade = gradeSys($total,$grade_r['A'],$grade_r['B'],$grade_r['C'],$grade_r['D'],$grade_r['E'],$grade_r['F']);

	if ($grade=='E' OR $grade=='F' ) {
		$deficiency++;
	}
	if ($term_id != 3) {
		
		$update_score = $conn->query("UPDATE contineous_accessment SET ca1='$ca1', ca2='$ca2', ca3='$ca3', exam='$exam', total='$total',grade='$grade', position='$pos' WHERE student_info_id='$stid' AND subject_id='$sbid' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");
		echo mysqli_error($conn);
		if ($update_score) {
			$ct++;
		}else{
			$cn++;
		}
	//end save subject score
	//do all subject available 
		$sql = $conn->query("SELECT *, SUM(total) AS allT, COUNT(total) AS cnt FROM contineous_accessment WHERE student_info_id='$stid' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");
		echo mysqli_error($conn);
		$o= $sql->fetch_assoc();
		$total = $o['allT'];
		$count = $o['cnt'];
		$data1[] = array('id'=>$stid,'total'=>$total,'count'=>$count, 'def'=>$deficiency);

	}else{
		$Ttotal = $conn->query("SELECT SUM('total') as firstAndSecondTermTotal FROM contineous_accessment WHERE session_id='$session_id' AND term_id != '3' AND class_id='$bid' AND subject_id='$sbid'  ");
		if ($Ttotal->num_rows> 0) {
			$fTtotal = $Ttotal->fetch_assoc();
			$exam_avg =  (intval($fTtotal['firstAndSecondTermTotal']) + intval($exam))/3;
		}else{
			$exam_avg = 0;
		}

		$ttotal = intval($ca1)+ intval($ca2) + intval($ca3) + intval($exam_avg);
		$grade3 = gradeSys($ttotal,$grade_r['A'],$grade_r['B'],$grade_r['C'],$grade_r['D'],$grade_r['E'],$grade_r['F']);

		$update_score = $conn->query("UPDATE contineous_accessment SET ca1='$ca1', ca2='$ca2', ca3='$ca3', exam='$exam',exam3='$exam_avg',  total='$total', total3='$ttotal', grade='$grade', grade3='$grade3', position='$pos' WHERE student_info_id='$stid' AND subject_id='$sbid' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");		

		echo mysqli_error($conn);
		if ($update_score) {
			$ct++;
		}else{
			$cn++;
		}
	//end save subject score
	//do all subject available 
		$sql = $conn->query("SELECT *, SUM(total) AS allT, COUNT(total) AS cnt, SUM(total3) AS allT3 FROM contineous_accessment WHERE student_info_id='$stid' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");
		echo mysqli_error($conn);
		$o= $sql->fetch_assoc();
		$total = $o['allT'];
		$total3 = $o['allT3'];
		$count = $o['cnt'];
		$data1[] = array('id'=>$stid,'total'=>$total,'count'=>$count, 'def'=>$deficiency, 'total3'=>$total3);
	}
	

//end do all subject available
}
$l= 1;
$l3= 1;
if ($term_id != 3) {
	foreach ($data1 as $key) {
			$stid1 = $key['id'];
			$total = $key['total'];
			$count = $key['count'];
			$deficiency_count = $key['def'];
		$total_score_expected = $count*100;
		$all_result_grade = ceil(($total/$total_score_expected)*100);
		$overall_grade = gradeSys($all_result_grade,$grade_r['A'],$grade_r['B'],$grade_r['C'],$grade_r['D'],$grade_r['E'],$grade_r['F']);

			if($l==1){
				$pos = ordinal($l);
				$lastT = $total;
			}else{
				if($lastT==$total){
					$l-=1;
					$pos = ordinal($l);
					$lastT = $total;
				}else{
					$pos = ordinal($l);
					$lastT = $total;
				}
			}
			$l++;
		$chk_r = $conn->query("SELECT * FROM term_result WHERE student_info_id='$stid1' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");

		if ($chk_r->num_rows>0){
			$update_all = $conn->query("UPDATE term_result SET total = '$total', position='$pos',o_grade='$overall_grade',deficiency_count='$deficiency_count' WHERE student_info_id='$stid1' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");
			//echo mysqli_error($conn);
		}

	}
}else{
	foreach ($data1 as $key) {
			$stid1 = $key['id'];
			$total = $key['total'];
			$count = $key['count'];
			$total3 = $key['total3'];
			$deficiency_count = $key['def'];
		
			$total_score_expected = $count*100;
		
			$all_result_grade = ceil(($total/$total_score_expected)*100);
			$all_result_grade3 = ceil(($total3/$total_score_expected)*100);

			$overall_grade = gradeSys($all_result_grade,$grade_r['A'],$grade_r['B'],$grade_r['C'],$grade_r['D'],$grade_r['E'],$grade_r['F']);
			$overall_grade3 = gradeSys($all_result_grade,$grade_r['A'],$grade_r['B'],$grade_r['C'],$grade_r['D'],$grade_r['E'],$grade_r['F']);

			if($l==1){
				$pos = ordinal($l);
				$lastT = $total;
			}else{
				if($lastT==$total){
					$l-=1;
					$pos = ordinal($l);
					$lastT = $total;
				}else{
					$pos = ordinal($l);
					$lastT = $total;
				}
			}
			$l++;

			if($l3==1){
				$pos3 = ordinal($l3);
				$lastT3 = $total3;
			}else{
				if($lastT3==$total3){
					$l3-=1;
					$pos3 = ordinal($l3);
					$lastT3 = $total3;
				}else{
					$pos3 = ordinal($l3);
					$lastT3 = $total3;
				}
			}
			$l3++;
		$chk_r = $conn->query("SELECT * FROM term_result WHERE student_info_id='$stid1' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");

		if ($chk_r->num_rows>0){
			$update_all = $conn->query("UPDATE term_result SET total = '$total',total3='$total3', position3='$pos3', position='$pos',grade='$overall_grade',grade3='$overall_grade3', deficiency_count='$deficiency_count' WHERE student_info_id='$stid1' AND class_id='$bid' AND term_id='$tid' AND session_id='$sid'");
			//echo mysqli_error($conn);
		}

	}
}
echo 1;
/*
	if ($cn == 0 && $ct>0) {
		echo 1;// all uploaded
	}else if ($ct==0 && $cn>0) {
		echo 0; //no upload
	}else if ($ct>0 && $cn>0) {
		echo $cn;//not all uploaded
	}
	*/
?>