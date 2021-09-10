<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
			$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../staff_login.php');
	}
	
	//$staff_info_id = $_POST['token'];
	include_once('../php/staff_data.php');


?>


<div class="row" style="color:#067">
	
	<div class="col-lg-12">
		<div style="width:20%;height:200px;margin-top:-10px;border:1px solid #ddd;background:#ccc;float:left">
			<img id="staff_pics" src="<?php echo $user_pic; ?>" style="width:100%;height:100%;">
		</div>
		<div style="margin-left:21%">
			<h4><?php echo $full_name; ?></h4>
			<h5><span class="glyphicon glyphicon-envelope"></span> <?php echo $email_address; ?></h5>
			<h5><span class="glyphicon glyphicon-map-marker"></span> Teaching Staff</h5>
			<h5><a href="#" onclick="load_change_staff_picture(<?php echo $staff_info_id;?>)"><span class="glyphicon glyphicon-edit"></span> Edit Profile Picture</a></h5>
		</div>
	</div>



</div >
		
						<div >
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							  <div class="panel panel-primary" style="opacity:0.9">
								<div class="panel-heading" role="tab" id="headingOne">
								  <h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
									  <span class="glyphicon glyphicon-list-alt"></span>  Your Public Profile
									</a>
								  </h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
								  <div class="panel-body" style="font-size:12px">
										<div class="form-horizontal">
										  <div class="form-group">
											<label for="inputEmail3" class="col-sm-2 control-label">First Name</label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="first_name" value = "<?php echo $first_name; ?>" placeholder="First Name">
											</div>
										  </div>
										  <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Last Name</label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="last_name" value = "<?php echo $last_name; ?>" placeholder="Last Name">
											</div>
										  </div>
										    <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Other Name</label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" value = "<?php echo $other_name; ?>" id="other_name" placeholder="Other Name">
											</div>
										  </div>
										    <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Phone Number </label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="phone_number" value = "<?php echo $phone_number; ?>" placeholder="Phone Number ">
											</div>
										  </div>
										   <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Other Phone Number </label>
											<div class="col-sm-10">
											<input type="number" class="form-control add_new_staff_form_input" id="other_phone_numbers" placeholder="Other Phone Numbers">
											</div>
										  </div>
										     <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Email Address </label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="email_address" value = "<?php echo $email_address; ?>" placeholder="Email Address ">
											</div>
										  </div>
										    <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label" >Facebook Username </label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="face_book_name" value = "<?php echo $face_book_name; ?>" placeholder="Facebook Username ">
											</div>
										  </div>
										    <div class="form-group">
												<label for="marital_status" class="col-sm-2 control-label">Marital Status</label>
													<div class="col-sm-10">
													<select  class="form-control add_new_staff_form_input" id="marital_status">
														<option value=''>--select marital status--</option>
														
														<option <?php if($marital_status == 'single') echo 'selected';?> value='single'>Single</option>
														<option <?php if($marital_status == 'married') echo 'selected';?> value='married'>Married</option>
													</select>
													</div>
											  </div>
										   <div class="form-group" >
											<label for="religion" class="col-sm-2 control-label">Religion</label>
												<div class="col-sm-10">
												<select  class="form-control " id="religion">
													<option value=''>--select Religion--</option>
													<option <?php if($religion == 'Christian') echo 'selected';?> value='Christian'>Christianity</option>
													<option <?php if($religion == 'Muslim') echo 'selected';?>  value='Muslim'>Islam</option>
												</select>
												</div>
										  </div>
											
										<div class="form-group" >
											<label for="religion" class="col-sm-2 control-label">Date of Birth</label>
												<div class="col-sm-10">
												<?php
													if( $date_of_birth != ''){
												?>
													<input type="text" class="form-control add_new_staff_form_input" value = "<?php echo $date_of_birth; ?>" id="date_of_birth" placeholder="Date of Birth">
													
												<?php 
												}else{
												?>
												<input type="date" class="form-control add_new_staff_form_input" id="date_of_birth" placeholder="Date of Birth">
													
												<?php 
												}
												?>
												</div>
										  </div>
										  <div class="form-group" >
											<label for="religion" class="col-sm-2 control-label">State Of Origin</label>
												<div class="col-sm-10">
														<select  class="form-control add_new_staff_form_input" id="state" onchange="load_lga(this.value)">
															<option value=''>--select state--</option>
																<?php 
																	$sql_state = mysqli_query($conn,"SELECT * FROM states");
																	if(mysqli_num_rows($sql_state) > 0){
																		while($state_array = mysqli_fetch_array($sql_state)){
																				$state_idd = $state_array['state_id'];
																				$state = $state_array['name'];
																			if($state_id == $state_idd)
																			echo '	<option selected value='.$state_id.'>'.$state.'</option>';
																			else
																			echo '	<option value='.$state_id.'>'.$state.'</option>';
																		}
																	}
																?>
														</select>
												
												</div>
										  </div>
								
										 <div class="form-group" >
											<label for="religion" class="col-sm-2 control-label">Local Government Area (LGA)</label>
												<div class="col-sm-10">
														<select  class="form-control add_new_staff_form_input" id="lga">
															<option value=''>--select LGA--</option>
															<?php 
																$sql_lga = mysqli_query($conn,"SELECT * FROM lga ");
																	if(mysqli_num_rows($sql_lga) > 0){
																		
																		while($lga_array = mysqli_fetch_array($sql_lga)){
																				$lga_idd = $lga_array['local_id'];
																				$title = $lga_array['title'];
																				if($lga_id == $lga_idd)
																				echo '<option selected value='.$lga_idd.'>'.$title.'</option>';
																			else
																				echo '<option value='.$lga_idd.'>'.$title.'</option>';
																		}
																	}
															
															?>
														</select>
												
												</div>
										  </div>
										  
										  <div class="form-group" >
											<label for="religion" class="col-sm-2 control-label">Tribe</label>
												<div class="col-sm-10">
													<input type="text" class="form-control add_new_staff_form_input" value = "<?php echo $tribe; ?>" id="tribe" placeholder=" ">
												
												</div>
										  </div>
										  <div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
											  <button type="submit" class="btn btn-default" onclick="update_staff_public_profile(<?php echo $staff_info_id; ?>)">Save</button>
												<span id="bio_status"></span>
											</div>
										  </div>
										</div>


								 </div>
								</div>
							  </div>
							  <div class="panel panel-primary" style="opacity:0.9">
								<div class="panel-heading" role="tab" id="headingTwo">
								  <h4 class="panel-title">
									<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									<span class="fa fa-briefcase"></span> Your Bank Details
									</a>
								  </h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
								  <div class="panel-body" style="font-size:12px">
											<form class="form-horizontal" >
										  <div class="form-group">
											<label for="inputEmail3" class="col-sm-2 control-label">Account Name</label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="account_name" placeholder="Account Name">
											</div>
										  </div>
										  <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Account Number</label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="account_number" placeholder="Account Number">
											</div>
										  </div>
										    <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">BVN</label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="account_bvn" placeholder="BVN">
											</div>
										  </div>
										    <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Sort Code </label>
											<div class="col-sm-10">
											  <input type="text" class="form-control" id="sort_code" placeholder="Sort Code">
											</div>
										  </div>
										     <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Account Type </label>
											<div class="col-sm-10">
											  <select class="form-control" id="account_type">
													<option value="">-- Select Account Type</option>
													<option value="Savings">Savings</option>
													<option value="Current">Current</option>
												
											  </select>
											</div>
										  </div>
										    <div class="form-group">
											<label for="inputPassword3" class="col-sm-2 control-label">Bank </label>
											<div class="col-sm-10">
											  <select class="form-control" id="bank">
													<option value="">-- Select Bank</option>
													<option value="UBA">UBA</option>
													<option value=">First Bank">First Bank</option>
													<option value=">GTB">GTB</option>
													<option value=">Diamond Bank">Diamond Bank</option>
												
											  </select>
											</div>
										  </div>
										
										  <div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
											  <button type="submit" class="btn btn-default">Save</button>
											</div>
										  </div>
										</form>
								
									</div>
								</div>
							  </div>
							  <div class="panel panel-primary" style="opacity:0.9">
								<div class="panel-heading" role="tab" id="headingThree">
								  <h4 class="panel-title">
									<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									 <span class="fa fa-users"></span> Your Next of Kin
									</a>
								  </h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
								  <div class="panel-body">
									Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
								  </div>
								</div>
							  </div>
							</div>
						</div >