<?php session_start(); ?>
<?php 
include_once('../php/connection.php');
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
						$output_table .='<table border="2" class="table table-bordered">
												<caption>
														<h2 style="text-align:center;color:blue"> PEACE SECONDARY SCHOOL MOKWA NIGER STATE</h2>
														<h3 style="text-align:center;margin-top:-5px">Attendance of '.$class_name.'for The Month of <b style="color:red">  '.$month_full.'</b></h3>
												</caption>
												<thead>
												<tr>
													<th>SN</th>
													<th style="width:260px">Student Name</th>
													<th colspan="'.$number_of_days.'"></th>
													';
													
												$output_table .= '</tr>
												</thead><tbody>
										';
											
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
																		$output_table .= '
																		<tr>
																			<td>'.$sn.'</td>
																			<td>'.$full_name.'</td>';
																		
																					
																						for($day_in_month = 1;$day_in_month <= $number_of_days;$day_in_month++){
																							 
																							 $att_query = mysqli_query($conn,"select * from student_attendance where student_info_id = '$student_info_id' and session_id = '$session_id' and class_id = '$cl_id' and  year = '$current_year' and month = '$att_month' and day = '$day_in_month'  ") or die(mysqli_error($conn));
																								$att_query_num_rows = mysqli_num_rows($att_query);
																								if($att_query_num_rows > 0){
																									$att_rows = mysqli_fetch_array($att_query);
																										
																										$status = $att_rows['status'];
																										 if($status == 1)
																											$output_table .= ' <td>1</td>';
																										else
																											$output_table .=' <td>0</td>';
																									
																																									
																								}else{
																									$output_table .= '<td>0</td>';
																								}
																							
																							
																						}
																				
																			$output_table .= '
																			
																		</tr>';
																		$sn++;
																}
													}
												}
									
										
										$output_table .= '</tbody></table>';
							
							header("Content-type : application/xls");
							header("Content-Disposition : attachment; filename = '".$month_abr.$current_year.".xls'");
							echo $output_table;
							
							
						
					//}
?>