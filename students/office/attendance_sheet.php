<?php
				include_once('../php/connection.php');	
				
							
							
							if(isset($_GET['token'])){
								$class_id_hash = $_GET['token'];
										$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_idd = $class_array['class_id'];
												$class = $class_array['class_name'];
												$hash_class = md5($class_idd);
													if($class_id_hash == $hash_class){
														$class_id = $class_idd;
													}
											}
							}else{
								$class_id = 1;
							}
					
					// Get the current term
							$term = mysqli_query($conn,"select * from term  where status = '1'") or die(mysqli_error($conn));
							$term_array = mysqli_fetch_array($term);
							$term = $term_array['term'];
							$term_id = $term_array['id'];
							$description = $term_array['description'];
							
					/*	Beginning of nav tab		*/
							echo '<ul class="nav nav-tabs" role="tablist" id="myTab">';
							
							//load all the months from database
									$get_months = mysqli_query($conn,"select * from months ");
									$months_num_rows  = mysqli_num_rows($get_months);
									
									$sn = 1; // set index of month array
									$month_array = array(); // creating array that will hold month title in abr form
									$days_array = array('S','M','T','W','TH','F','S'); // days in a week array
									
									while($months = mysqli_fetch_array($get_months)){
										$month_id  = $months['month_id']; // Months id 1. Jan 2. Feb 3. Mar 4. Apr etc
										$month_full  = $months['month_full']; // full like : January
										$month_abr  = $months['month_abr']; // abr like Jan
										
										$class = ''; // active nav tab class
											if($sn == 1){
												$class = 'active';
											}
									$month_array[$sn]= $month_abr; // storing all months abr in array for later user
									//nav tabs
										echo '  <li role="presentation" class="'.$class.'" name='.$month_abr.'>	<a href="#"  aria-controls="'.$month_abr.'" role="tab" data-toggle="tab">'.$month_full.'</a></li>
											';
										$sn++;
									}
									
									echo '</ul>';
						/*	end of nav tab		*/
						/*	beginning of  tab content		*/
						
									echo '<div class="tab-content" style="background-color:#ffffff ">';
									
						// Beginning of month loop
						// 
									for($month_id = 1;$month_id <= $months_num_rows;$month_id++){ // this loop will go the number of months we have 1.e 12 times
										$abr = $month_array[$month_id]; // getting back months abbreviation that is stored in month_array
										$class = '';
										
										if($month_id == 1){
												$class = 'active';
											}
											$att_tok = $class_id.','.$month_id;
										echo '<div role="tabpanel" class="tab-pane '.$class.'" id="'.$abr.'" style=""> 
										
										<h4 class="text-center">'.$abr.' '.$current_year.' <span style="float:right;margin-right:20px" id="export_att_btn'.$month_id.'" class="btn btn-primary" onclick="attendance_toExcel('.$att_tok.')"><span style="margin-right:2px" class="glyphicon glyphicon-print"></span> Export Excel</span></h4>';
										
									//Beginning of attendance sheet table	
										echo '<table class="table table-stripped table-bordered">
												<thead>
												<tr>
													<th>SN</th>
													<th style="width:260px">Student Name</th>
													';
															$get_months2 = mysqli_query($conn,"select * from months where month_id = '$month_id'");
															if($get_months2){
																
																$number_of_days_array = mysqli_fetch_assoc($get_months2); 
																 $number_of_days = $number_of_days_array['number_of_days']; // number of days in a particular  month
																	$day = 0;
																	$week = 1;
																	for($j = 1;$j <= $number_of_days;$j++){
																		
																				if($day == 0 or $day == 6){
																					echo '<th style="color:red">'.$j.'</th>';
																				}else if($day == 2 or $day == 4){
																						echo '<th style="color:blue">'.$j.'</th>';
																				
																				}
																				else {
																					echo '<th>'.$j.'</th>';
																				}
																		$day++;
																		if($day >6){
																			$day = 0;
																			//echo '<th colspan="2"> Wk '.$week.'</th>';
																			$week++;
																			
																		}
																		
																	}
															}else{
																echo 'failed...';
															}
												echo '</tr>
												</thead><tbody>
										';
											if(isset($_GET["pn"])){
													$pn = $_GET["pn"];
												}else{
													$pn = 1;
												}
											
										
										
										// i get the cout of rows i wish to select from the db
										$countt = "select COUNT(student_class_id) from student_classes where class_id = '$class_id' and status = '1'";
										$do_query = mysqli_query($conn,$countt);
										
										// whats the total row count? here is it
										$row = mysqli_fetch_row($do_query);
										
										// total row count
										$total_rows = $row[0];
										
										// how many items per page
										
										$item_pp = 5;

										// last page number_format
										$last = ceil($total_rows/$item_pp);

										//non - negative constraint on the last page number 
										if($last < 1)
										{
											$last = 1;
										}
										
											if($pn < 1)
											{
												$pn = 1;
											}
											else if($pn > $last)
											{
												$pn = $last;
											}
											
											$limit = ($pn - 1) * $item_pp.','.$item_pp;
											$sql_all_class = "select * from student_classes where class_id = '$class_id' and status = '1' ORDER BY student_class_id LIMIT $limit";
											$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
												$num_rows_all_class = mysqli_num_rows($query_all_class);
												if($num_rows_all_class > 0){
													if(isset($_GET["sn"])){
															$sn = $_GET["sn"];
														}else{
															$sn = 1;
														}
													
														if($sn < 1){
															$sn = 1;
														}
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
																		echo '
																		<tr>
																			<td>'.$sn.'</td>
																			<td>'.$full_name.'</td>';
																			$get_months2 = mysqli_query($conn,"select * from months where month_id = '$month_id'");
																				if($get_months2){
																					$number_of_days_array = mysqli_fetch_assoc($get_months2); 
																					 $number_of_days = $number_of_days_array['number_of_days'];
																						$day = 0;
																						for($day_in_month = 1;$day_in_month <= $number_of_days;$day_in_month++){
																							 $check_query = mysqli_query($conn,"select * from student_attendance where student_info_id = '$student_info_id' and session_id = '$session_id' and year = '$current_year' and term_id = '$term_id' and month = '$month_id' and day = '$day_in_month' and class_id = '$class_id' and status = '1'") or die(mysqli_error($conn));
																											$num_rows_check = mysqli_num_rows($check_query);
																												if($num_rows_check > 0){
																												echo '<td><input type="checkbox" checked onclick="mark_attendance('.$student_info_id.','.$month_id.','.$day_in_month.')"></td>';
																									
																												}else{
																													echo '<td><input type="checkbox"  onclick="mark_attendance('.$student_info_id.','.$month_id.','.$day_in_month.')"></td>';
																								
																												}
																									
																							$day++;
																							if($day >6){
																								$day = 0;
																								//echo '<th colspan="2"></th>';
																							}
																							
																						}
																				}else{
																					echo 'failed...';
																				}
																			echo '
																			
																		</tr>';
																		$sn++;
																}
													}
												}
									
										
										echo '
											</tbody>
											</table>
										</div>';
										
									} // end of month loop
									
									echo '</div>';
									$paginationCtrls = "";
		if($last != 1)
		{
			if($pn > 1)
			{
				
				$paginationCtrls .= '	<li> <a href="attendance.php?token='.md5($class_id).'&pn='.($pn-1).'&sn='.($sn-$item_pp*2).'" aria-label="Previous"><span aria-hidden="true">&laquo;</span> </a></li>';	
			}
				$paginationCtrls .= ' <li class="active"><a href="#">'.$pn.' <span class="sr-only">(current)</span></a></li>';
			if($pn !=$last)
			{
			
				$paginationCtrls .= '<li> <a href="attendance.php?token='.md5($class_id).'&pn='.($pn+1).'&sn='.($sn).'"   aria-label="Next"><span aria-hidden="true">&raquo;</span> </a></li>';
			}
		}
							
							?>
							
							
							
								<p>	
													
																<nav  style="margin-bottom:0px">
																			<ul class="pagination pagination-sm">
																					<?php echo $paginationCtrls; ?>
																				</ul>
																			</nav>
															</p>