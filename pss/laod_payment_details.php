						
									 <?php
										include_once('../php/connection.php');
											if(isset($_POST['token']))
												$school_section_id = mysqli_real_escape_string($conn,$_POST['token']);
											else
												$school_section_id = 1;
												
												$sql_section_name = "select * from school_section";
												$query_section_name = mysqli_query($conn,$sql_section_name);
												$num_rows_name= mysqli_num_rows($query_section_name);
													if($num_rows_name > 0){
														while($name_array =  mysqli_fetch_assoc($query_section_name)){
															$section_name = $name_array['section_name'];
															$school_section_id = $name_array['school_section_id'];
															
												$sql_section_payment = "select distinct sex from payment_details where school_section_id = '$school_section_id' order by sex desc";
												$query_section_payment = mysqli_query($conn,$sql_section_payment);
												$num_rows1= mysqli_num_rows($query_section_payment);
													if($num_rows1 > 0){
																	
															
														while($section_array1 = mysqli_fetch_array($query_section_payment)){
															$gender = $section_array1['sex'];
																
																	 $sql_section_payment2 = "select * from payment_details where school_section_id = '$school_section_id' and (sex='$gender' or sex='All') and status = '1'";
																
																$query_section_payment2 = mysqli_query($conn,$sql_section_payment2);
																$num_rows2= mysqli_num_rows($query_section_payment2);
																	if($num_rows2 > 0){
																		
																				$sql_sum_amount = "select SUM(amount) from payment_details where school_section_id = '$school_section_id' and (sex='$gender' or sex='All') and status = '1'";
																				$query_sum_amount = mysqli_query($conn,$sql_sum_amount);
																				$sum_row = mysqli_fetch_row($query_sum_amount);
																				$count = $sum_row[0];
																			
																			if($gender == 'M')
																					$gender = 'Male students';
																				else if($gender == 'All')
																					$gender = 'All students both Male and Female';
																				else
																					$gender = 'Female students';
																		echo '
																			<table class="table table-bordered" style="width:100%">
																				<caption>'.$section_name.' <b>('.$gender.')</b></caption>
																					<tr>
																						<th style="width:2%">sn</th>
																						<th>Description</th>
																						<th class="text-center">Amount</th>
																						
																		';
																		$sn = 1;
																		
																		
																			while($section_array2 = mysqli_fetch_array($query_section_payment2)){
																			$payment_details_id = $section_array2['payment_details_id'];
																			$payment_description = $section_array2['payment_description'];
																			$amount = $section_array2['amount'];
																				echo '
																						<tr>
																							<td class="text-center">'.$sn++.'</td>
																							<td>'.$payment_description.'</td>
																							<td class="text-center">'.$amount.'</td>
																							
																						</tr>
																					';
																			}
																		echo '	
																			<tr>
																				<td></td>
																				<td colspan="2"><b> Total =  N'.$count.'</b></td>
																			</tr>
																		</table>';
																	}
														}
													}
																				
														}
														
													}
												
												

									?>