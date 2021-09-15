
<?php

$formTeacher = mysqli_query($conn, "SELECT * FROM `staff_classes` AS c INNER JOIN staff_info as s ON s.staff_info_id = c.staff_info_id  WHERE c.class_id='$class_id' AND c.session_id = '$session_id' ");
$ff = mysqli_num_rows($formTeacher);
if ($ff > 0) {
	$clb = mysqli_fetch_array($formTeacher);
	$formTeacher = $clb['title'] . '. ' . $clb['first_name'] . ' ' . $clb['other_name'] . ' ' . $clb['last_name'];
} else {
	$formTeacher = "";
}


////////////////// school head////////////////
if ($class_->school_section_id == 3 || $class_->school_section_id == 4) {
	$school_head_id = 5;
} else {
	$school_head_id = 6;
}

$stmt = $pdo->prepare("select * from school_head where id = ?");
$stmt->execute(array($school_head_id));
$school_head_ = json_decode(json_encode($stmt->fetch(PDO::FETCH_ASSOC)));
$school_head_staff_id = $school_head_->staff_info_id;

$school_head_staff = $staff_obj->find($school_head_staff_id);
$head_name = $staff_obj->formatName($school_head_staff);


$classDistinctScores = [];
$classDistinctScores_arr_obj = $mark_obj->classDistinctScores(array($session_id, $term_id, $class_id));
foreach ($classDistinctScores_arr_obj as $key => $classDistinctScores_obj) {
	array_push($classDistinctScores, $classDistinctScores_obj->total);
}

$position_index = array_search($getStudentTotalScore->total_score, $classDistinctScores);
$position = $position_index + 1;

$total_attendance = $mark_obj->getStudentTotalAttendance(array($student_info_id, $session_id, $term_id, $class_id));

$getStudentComments = $mark_obj->getStudentComments(array($student_info_id, $session_id, $term_id, $class_id));
$getResumptionDate = $mark_obj->getResumptionDate($class_->school_section_id, $session_id, $term_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Elmaasum:: Report Card</title>
	<link rel="stylesheet" href="http://elmaasum.com/css/bootstrap.css">
	<style>
		.card {
			/* Add shadows to create the "card" effect */
			box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
			transition: 0.3s;
		}

		/* On mouse-over, add a deeper shadow */
		.card:hover {
			box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
		}

		#content {
			width: 100%;
			margin: 5px auto;

			padding: 5px 20px;
			border: 2px solid #000;
		}

		table {
			width: 100%;
			font-size: 11px;
		}

		table tr td {
			padding: 5px;
			font-size: 11px;
		}

		table tr th {
			text-align: center;
		}
	</style>
</head>

<body id="body">

	<div id="content" class="card">

		<div>
			<div>

				<table>
					<tr>
						<td width="40%">

							<h5 class="text-center"><img src="../../images/<?= $school_image; ?>" alt="" width="50px">
								<br>EL-MAASUM ACADEMY
								<br> e-Report Card for <?= $term_full . ' ' . $session_full . ' Academic Session'; ?>
							</h5>
						</td>
						<td width="60%" style="padding:0px">
							<table style="margin:0px" border="2">
								<tr>
									<td>Student Name: <h6><b><?= strtoupper($student_obj->formatName($student_)); ?></b> </h5>
									</td>
									<td>Class: <h6><b><?= $class_->class_name; ?></b> </h5>
									</td>
									<td>Admission Number: <h5><?= $student_->adm_no; ?> </h5>
									</td>
									<td>Total Attendance: <h5> <?= $total_attendance;?></h5>
									</td>
								</tr>
							</table>

						</td>
					</tr>



				</table>

				<?php

				$sn = 1;
				$tr ='';
				$n = 0;

				$student_grades = [
				];
				foreach ($subjects as $key => $subject) {

					$data = [
						'student_info_id' => $student_info_id,
						'class_id' => $class_id,
						'session_id' => $session_id,
						'subject_id' => $subject->id,
						'term_id' => $term_id
					];

					$marks = $mark_obj->get($data);
					if (!empty($marks)) {
				
						$tr .='<tr>
							<td class="text-center"> '.$sn .'</td>
							<td> '.strtoupper($subject->subject).' </td>
							<td class="text-center">'. ( $marks->ca1 ? $marks->ca1 : "-" ).'</td>
							<td class="text-center">'. ( $marks->ca2 ? $marks->ca2 : "-" ).'</td>
							<td class="text-center">'. ( $marks->ca3 ? $marks->ca3 : "-" ).'</td>
							<td class="text-center">'. ( $marks->ca4 ? $marks->ca4 : "-" ).'</td>
							<td class="text-center">'. ( $marks->ca5 ? $marks->ca5 : "-" ).'</td>
							<td class="text-center">'. ( $marks->exam ? $marks->exam : "-" ).'</td>
							<td class="text-center">'. ( $marks->total ? $marks->total : "-" ).'</td>
							';
								
								$total = $marks->total ? $marks->total : '-';
								if ($total == '-') {
									$grade =  '-';
								} else {
									$grade =  $marks->grade;
									array_push($student_grades, $grade);
								}
							
                                $n++;
							$tr .='<td class="text-center">'.$grade.'</td>
						</tr>';
			

						$sn++;
					}
				}
				$grade_nums=array_count_values($student_grades);
				$num_A = (isset($grade_nums['A']) ? $grade_nums['A'] : 0);
				$num_B = (isset($grade_nums['B']) ? $grade_nums['B'] : 0);
				$num_C = (isset($grade_nums['C']) ? $grade_nums['C'] : 0);
				$num_D = (isset($grade_nums['D']) ? $grade_nums['D'] : 0);
				$num_E = (isset($grade_nums['E']) ? $grade_nums['E'] : 0);
				$num_F = (isset($grade_nums['F']) ? $grade_nums['F'] : 0);
				$comment_ = '';
				if(count($grade_nums) == 1 && $num_A >=1){
					$comment_ = 'Excellent result, keep it up.';
				}else if(count($grade_nums) == 1 && $num_B >=1){
					$comment_ = 'Excellent result, keep it up.';
				}else if(count($grade_nums) == 2 && $num_A >=1 && $num_B >=1){
					$comment_ = 'Excellent result, keep it up.';
				}else if(count($grade_nums) == 1 && $num_F >= 1){
					$comment_ = 'Very poor result, wake up from your slumber and work hard next term';
				}else if(count($grade_nums) == 1 && $num_C >= 1){
					$comment_ = 'Very good result, try harder next time.';
				}else if($num_A >=1 && $num_C >= 1 && $num_F == 0){
					$comment_ = 'Very good result, try harder next time.';
				}else if($num_C >= 1 && $num_F >= 1){
					$comment_ = 'Good result, try harder next time.';
				}else if(($num_B >= 1 || $num_C >= 1 || $num_D >= 1 ) && $num_F == 0){
					$comment_ = 'Good result, try harder next time.';
				}else if(($num_D >= 2 || $num_E >= 2 ) && $num_F >= 1){
					$comment_ = 'Poor result, wake up from your slumber and work hard next term';
				}else if(($num_D >= 5 || $num_E >= 5 ) && $num_F >= 1){
					$comment_ = 'Fair result, improve next time.';
				}

				$sn = 1;
				?>
				<h6>Total Marks: <b><?= $getStudentTotalScore->total_score; ?></b> | Avergage Score: <?= round(($n > 0 ? $getStudentTotalScore->total_score/$n : 0)); ?> | No. IN Class: <?= $totalNumberInClass; ?>
					<span style="float:right">Position in Class: <b><?= $mark_obj->formatPosition($position); ?></b> </span>
				</h6>

				<table border="2">
					<thead>
						<tr>
							<th>SN</th>
							<th>SUBJECT</th>
							<th>CA1</th>
							<th>CA2</th>
							<th>CA3</th>
							<th>P/Q</th>
							<th>ASS</th>
							<th>EXAM</th>
							<th>TOTAL</th>
							<th>GRADE</th>
						</tr>
					</thead>
					<?= $tr ;?>

				</table>

				<?php
				$allPsycomotor = $mark_obj->allPsycomotor();
				$allAffectiveDomain = $mark_obj->allAffectiveDomain();
				?>
				<table>
					<tr>
						<td width="25%">
							<table border="2">
								<tr>
									<td>PSYCOMOTOR </td>
									<td>RATING </td>
								</tr>
								<?php
								foreach ($allPsycomotor as $key => $psycomotor) {
									$getStudentPsycomotor = $mark_obj->getStudentPsycomotor(array($student_info_id, $session_id, $term_id, $psycomotor->id));
									$rate = empty($getStudentPsycomotor) ? ' - ' : $getStudentPsycomotor->rate;
								?>
									<tr>
										<td><?= $psycomotor->p_name; ?></td>
										<td><?= $mark_obj->rate($rate); ?></td>
									</tr>
								<?php
								}
								?>
							</table>

						</td>
						<td width="25%">
							<table border="2">
								<tr>
									<td>AFFECTIVE DOMAIN </td>
									<td>RATING </td>
								</tr>
								<?php
								foreach ($allAffectiveDomain as $key => $affectiveDomain) {
									$getStudentAffectiveDomain = $mark_obj->getStudentAffectiveDomain(array($student_info_id, $session_id, $term_id, $affectiveDomain->id));
									$rate = empty($getStudentAffectiveDomain) ? ' - ' : $getStudentAffectiveDomain->rate;
								?>
									<tr>
										<td><?= $affectiveDomain->a_name; ?></td>
										<td><?= $mark_obj->rate($rate); ?></td>
									</tr>
								<?php
								}
								?>
							</table>
						</td>

						<td width="25%">
						<table border="2">
								<tr>
									<td> <b>Name [Form Teacher]:</b> <?= $formTeacher; ?></td>
								</tr>
								<tr>
									<td> <b>Comment [Form Teacher]:</b> <?= $getStudentComments->comment1; ?> </td>
								</tr>

								<tr>
									<td> <b>Name [School Head]:</b> <?= $head_name; ?></td>
								</tr>
								<tr>
									<td> <b>Comment [School Head]:</b> <?= $getStudentComments->comment2; ?></td>
								</tr>
							</table>

							<p>Vacation Date: <?= date_format(date_create($getResumptionDate->vacation), "jS M, Y"); ?> Resumption Date: <?= date_format(date_create($getResumptionDate->next_resumption), "jS M, Y"); ?></p>
							<p></p>
							<p> <b>--------------------------------------------------------------------- </b><br> School Head [Signature]</p>


						</td>
					</tr>

				</table>
			</div>
			<table>
				<tr>


				</tr>
			</table>

		</div>

	</div>

	<p class="text-center" onclick="window.print()"><button class="btn btn-default">Print Result</button></p>
</body>
<script language="javascript">
	//ClickheretoprintDiv('body')



	function ClickheretoprintDiv(div_id) {
		var disp_setting = "toolbar=yes,location=no,directories=yes,menubar=yes,";
		disp_setting += "scrollbars=yes,width=800, height=400, left=100, top=25";
		var content_vlue = document.getElementById(div_id).innerHTML;

		var docprint = window.open("", "", disp_setting);
		docprint.document.write('<html><head><title>.::Nicare</title> <link rel="stylesheet" href="../css/bootstrap.css">');
		docprint.document.write('</head><body onLoad="self.print()" style="width: 900px; height="auto" font-size:16px; font-family:arial;">');
		docprint.document.write(content_vlue);
		docprint.document.write('</body></html>');
		docprint.document.close();
		docprint.focus();
	}
</script>

</html>