	<?php
			
			$payment_details_id = $_POST['token'];
			include_once('../php/connection.php');
			$sql_section_n = "select * from  payment_details where payment_details_id = '$payment_details_id'";
			$query_section_n = mysqli_query($conn,$sql_section_n);
			$num_rows_n= mysqli_num_rows($query_section_n);
				if($num_rows_n > 0){
					$n_array =  mysqli_fetch_assoc($query_section_n);
					$school_section_id_n= $n_array['school_section_id'];
					$sex_n = $n_array['sex'];
					$category = $n_array['category'];
					$payment_description_n = $n_array['payment_description'];
					$amount_n = $n_array['amount'];
					
						if($sex_n == 'M'){
							$gn1 = 'selected';
						}else if($sex_n == 'F'){
							$gn2 = 'selected';
						}
						
						if($category == 1){
							$catOld = 'selected';
							$payment_gn = 'display:none';
						}else{
							$catNew = 'selected';
							$payment_gn = 'display:block';
							
						}
				}else{
					echo 'Oops something went wrong...';
					exit();
				}
	
	
	?>
	

<div class="col-lg-12" >
								
											<div class="form-inline">
											 <div class="form-group col-lg-2" >
											   <label for="section">Section</label><br>
									
									<select   id="section" class="fee_search_input" onchange="load_section_payment(this.value)" disabled>
												<?php 
													
													$sql_section = "select * from school_section where status = '1'";
													$query_section = mysqli_query($conn,$sql_section);
													$num_rows_section = mysqli_num_rows($query_section);
														if($num_rows_section > 0){
															while($section_array = mysqli_fetch_array($query_section)){
																	$section_name = $section_array['section_name'];
																	$school_section_id = $section_array['school_section_id'];
																	$section_name_abr = $section_array['section_name_abr'];
																	
																	if($school_section_id_n == $school_section_id){
																		$sl = 'selected';
																	}else{
																		$sl = '';
																	}
																	echo '<option value="'.$school_section_id.'" '.$sl.'>'.$section_name_abr.'</option>';
															}
														
														}
												
												?>
									</select>
									 </div>
									<div class="form-group col-lg-2" >
									<label for="section">Category</label><br>
									<select style=""  id="category" class="fee_search_input" onchange="payment_gender(this.value)">
											 <option value="2" <?php echo $catNew; ?>>New</option>
												  <option value="1" <?php echo $catOld; ?>>Old</option>	
									</select>
									
									 </div>
										
										  <div class="form-group  col-lg-2" style="" >
											
												<label for="payment_description">Description</label><br>
											<input type="text" class="fee_search_input" id="payment_description" value="<?php echo $payment_description_n;?>" placeholder="Description">
										 
										  </div>
										  <div class="form-group col-lg-2">
											
												<label for="payment_amount">Amount</label><br>
											<input type="text" class="fee_search_input" id="payment_amount" value="<?php echo $amount_n; ?>" placeholder="Amount">
										  
										  </div>
										   <div class="form-group col-lg-2" id="payment_gn" style="<?php echo $payment_gn; ?>">
										   <label for="gender">Gender</label><br>
													 <select style="" id="gender" class="fee_search_input">
												  <option value="M" <?php echo $gn1;?> >Male</option>
												  <option value="F" <?php echo $gn2;?>>Female</option>
												 
										
													</select>
											  </div>
											  	   
												<div class="form-group col-lg-2" id="add_payment_output">
														<label for=""></label><br>
														<button type="submit" class="btn btn-info" onclick="update_payment_details(<?php echo $payment_details_id.','.$school_section_id_n; ?>)">Update</button>
												</div>
										</div>
								</div>