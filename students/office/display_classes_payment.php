<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
	}
	else{
		header('location:../');
	}
	include_once('../php/connection.php');
	
	
	
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
			
				$sql_all_class = "select * from classes";
				$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
				$num_rows_all_class = mysqli_num_rows($query_all_class);
					if($num_rows_all_class > 0){
						$sn = 1;
						$tr .='
													<tr>
														<td class="text-center">Sn</td>
														<td class="text-left">Class</td>
														<td class="text-center">No. In Class</td>
														<td class="text-center">Complete Payment</td>
														<td class="text-center">Half Payment</td>
														<td class="text-center">Zero Payment</td>
														<td class="text-center"><a href="#"></a></td>
													</tr>
												';
						while($array_all_class = mysqli_fetch_array($query_all_class)){
							$class_name = $array_all_class['class_name'];
							$description = $array_all_class['description'];
							$class_id = $array_all_class['class_id'];
							
									$sql_all_class2 = "select * from student_classes where class_id = '$class_id' and (status = '1' or status = '2')";
									$query_all_class2 =  mysqli_query($conn,$sql_all_class2) or die(mysqli_error($conn));
									$total_students_inclass = mysqli_num_rows($query_all_class2);
									
									//Get total that pay complete ...
											$get_no_paid_all_sql = "select distinct student_info_id from school_fees where session_id = '$session_id' and term_id = '$term' and class_id ='$class_id' ";
											$get_no_paid_all_sql .= "and status='2'";
											$get_no_paid_all_query =  mysqli_query($conn,$get_no_paid_all_sql) or die(mysqli_error($conn));
											$gno_that_paid_all =  mysqli_num_rows($get_no_paid_all_query);
											
									//Get total that pay half ...
											$get_no_paid_half_sql = "select  distinct student_info_id from school_fees where session_id = '$session_id' and term_id = '$term' and class_id ='$class_id' ";
											$get_no_paid_half_sql .= "and (status = '1'  or ballance > 0) ";
											$get_no_paid_half_query =  mysqli_query($conn,$get_no_paid_half_sql) or die(mysqli_error($conn));
											$gno_that_paid_half =  mysqli_num_rows($get_no_paid_half_query);
											$half_paid = 0;
											if($gno_that_paid_half > 0){
												while($rows = mysqli_fetch_array($get_no_paid_half_query)){
													$student_info_id = $rows['student_info_id'];
														$get_no_paid_half_sql2 = "select  distinct student_info_id from school_fees where student_info_id ='$student_info_id' and session_id = '$session_id' and term_id = '$term' and class_id ='$class_id' ";
														$get_no_paid_half_sql2 .= "and (status = '2'  or ballance = 0) ";
														$get_no_paid_half_query2 =  mysqli_query($conn,$get_no_paid_half_sql2) or die(mysqli_error($conn));
														$gno_that_paid_half2 =  mysqli_num_rows($get_no_paid_half_query2);
															if($gno_that_paid_half2 <=0){
																$half_paid++;
															}
												}
											}
											
									
									// Get toal that did not pay any
											$no_that_pay_zero = $total_students_inclass - ($gno_that_paid_all + $half_paid);
												if($no_that_pay_zero < 0){
													$no_that_pay_zero = 0;
												}
												$tr .='
													<tr>
														<td class="text-center">'.$sn++.'</td>
														<td class="text-left">'.$class_name.'</td>
														<td class="text-center">'.$total_students_inclass.'</td>
														<td class="text-center">'.$gno_that_paid_all.'</td>
														<td class="text-center">'.$half_paid.'</td>
														<td class="text-center">'.$no_that_pay_zero.'</td>
														<td class="text-center"><a href="#" onclick="return false" onmousedown="view_all_paid_inclass('.$class_id.')">View All</a></td>
													</tr>
												';
												
														
																
														
						}
					}
					
		
?>
<div id="ca_heading">
					<h1 style="margin-top:10px;font-size:20px;text-align:center"><?php echo strtoupper($term_full).' '.$current_session.' ACADEMIC SESSION\'S PAYMENT SUMMARY FOR ALL THE CLASSES'; ?></h1>
					
</div>
<table class="table table-bordered" style="margin:20px 5px">
	<?php echo $tr; ?>
</table>