<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
			if(isset($_POST['token'])){
				$class_id = $_POST['token'];
				$class_iddd = $class_id;
			}
			else{
				exit();
			}
	}
	else{
		header('location:../');
	}
	include_once('../php/connection.php');

	$h1;
	$term;
	$status;
	$term_id;
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
	

	$session_id = $_POST['session_id'];
$get_current_session_id_query = mysqli_query($conn, "select * from session where section_id = '$session_id' ") or die(mysqli_error($conn));
$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
$session_id__dd = $get_current_session_id_arr['section_id'];
	
	if(isset($_POST['term_id'])){
	      $session_id = $_POST['session_id'];
			$get_current_session_id_query = mysqli_query($conn,"select * from session where section_id = '$session_id' ") or die(mysqli_error($conn));
			$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
			$session_id = $get_current_session_id_arr['section_id'];
			$current_session = $get_current_session_id_arr['section'];
			
		$term_id = $_POST['term_id'];
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
				$query_student_section=  mysqli_query($conn,"select * from classes where class_id = '$class_id' and status = '1'") or die(mysqli_error($conn));
							$num_rows_sql_student_section = mysqli_num_rows($query_student_section);		
									$class_section = mysqli_fetch_array($query_student_section);
									$school_section_id = $class_section['school_section_id'];
									$class_description = $class_section['description'];
									$class_name = $class_section['class_name'];
								
								
								
								if($school_section_id < 3){
									$h1 = $school_name;
								}else{
									$h1 = $school_name;
								}
									
				
						$sn = 1;
						$tr .='
													<tr>
														<td class="text-center">Sn</td>
														<td class="text-left">Name </td>
														<td class="text-center">Total Amount paid</td>
														<td class="text-center">Remaining Ballance</td>
														<td class="text-center"></td>
														
													</tr>
												';
							
									//

									if ($session_id__dd == $session_id) {
										$all_student_in_class = "select * from student_classes as s left join student_info as st on st.student_info_id= s.student_info_id where s.session_id = '$session_id' and s.class_id='$class_id' and (s.status = '1' or s.status = '2' ) ";
									} else {
										$all_student_in_class = "select * from student_classes as s left join student_info as st on st.student_info_id= s.student_info_id where s.session_id = '$session_id' and s.class_id='$class_id' and (s.status != '0' ) ";
									}
//$all_student_in_class = "select * from student_classes as s inner join student_info as st on st.student_info_id= s.student_info_id where s.session_id = '$session_id' and s.class_id='$class_id' and (s.status != '0' ) ";
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
											
											//Get amount expected to pay 
											/*$school_fees_sql = "select * from student_classes where student_info_id = '$student_info_id' and class_id='$class_id'";
											$school_fees_query = mysqli_query($conn,$school_fees_sql) or die(mysqli_error($conn));
											$school_fees_arr = mysqli_fetch_array($school_fees_query);
											$total_school_fees = $school_fees_arr['school_fees'];
											*/
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

								$tr .='
								<tr>
									<td class="text-center">'.$sn++.'</td>
									<td class="text-left">'.$full_name.'</td>
									<td class="text-center">N'.$total_amount_paid.'</td>
									<td class="text-center">N'.$remaining_ballance.'</td>
									<td class="text-center">'.$status.'</td>
									
							</tr>
							';
							}else{
									$tr .='
														<tr>
															<td class="text-center">'.$sn++.'</td>
															<td class="text-left">'.$full_name.'</td>
															<td class="text-center">N'.$total_amount_paid.'</td>
															<td class="text-center">N'.$total_school_fees.'</td>
															<td class="text-center"><b style="color:red"> No Payment </b></td>
															
													</tr>
													';	
							}
					}
				}

											
		
?>

<button class="btn btn-lg btn-primary" onclick="ClickheretoprintDiv('printable-area')">Print Now!</button>
<div id="printable-area">
<div id="ca_heading">
					<h1 style="margin-top:10px;font-size:20pt;"><?php echo $h1; ?></h1>
					<h2 style="color:#000"><?= $school_name;?></h2>
					<h2 style="color:red"><?php echo $class_name; ?> Payment Summary Table </h2>
</div>
<table class="table table-bordered" style="margin:20px 5px">
	<?php echo $tr; ?>
</table>
</div>

<h1 class="text-center"><a href="../php/print_view_all_paid_inclass.php?tok=<?php echo md5($class_iddd);?>&se=<?= $session_id;?>&term=<?= $term_id;?>" target="_Blank" class="btn btn-primary"> <span class="fa fa-print"></span>  Print Slip</a></h1>