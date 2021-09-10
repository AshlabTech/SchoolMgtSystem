		<form class="form-inline">
														<fieldset>
															 
														  <div class="form-group" style='margin-right:20px'>
														 
															<label class="" for="">select cureent session :  </label>
															<select class="form-control" id="current_session" onchange="change_academic_session(this.value)">
																			
																			<?php 
																				$query2 = mysqli_query($conn,"select * from session") or die(mysqli_error($conn));
																				while($class_array2 = mysqli_fetch_array($query2)){
																					$session_id = $class_array2['section_id'];
																					$session = $class_array2['section'];
																					$status = $class_array2['status'];
																						if($status == '1')
																							echo '<option value="'.$session_id.'" selected>'.$session.'</option>';
																						else
																								echo '<option value="'.$session_id.'" >'.$session.'</option>';
																				}
																		?>
																		</select>
														  </div>
														   <div class="form-group">
																	<label class="" for=""> Year : </label>
																	<select class="form-control" id="select_yr" onchange="change_academic_year(this.value)">
																	<?php 
																				$year = mysqli_query($conn,"select * from year order by year") or die(mysqli_error($conn));
																				while($year_array = mysqli_fetch_array($year)){
																					$current_year = $year_array['year'];
																					$id = $year_array['id'];
																					$status = $year_array['status'];
																					if($status == '1')
																						echo '<option value="'.$id.'" selected>'.$current_year.'</option>';
																					else
																				echo '<option value="'.$id.'" >'.$current_year.'</option>';
																						
																			
																				}
																		?>
																			</select>
																  </div>
																  
																   <div class="form-group">
																	<label class="" for=""> Term : </label>
																	<select class="form-control" id="select_tr" onchange="change_academic_term(this.value)">
																	<?php 
																				$year = mysqli_query($conn,"select * from term order by status desc") or die(mysqli_error($conn));
																				while($term_array = mysqli_fetch_array($year)){
																					$term = $term_array['term'];
																					$id = $term_array['id'];
																					$description = $term_array['description'];
																					$status = $term_array['status'];
																					if($status == '1')
																						echo '<option value="'.$id.'" selected>'.$description.'</option>';
																					else
																				echo '<option value="'.$id.'" >'.$description.'</option>';
																						
																			
																				}
																		?>
																			</select>
																  </div>
																  
														</fieldset>
													</form>