<?php
	$student_info_id = $_POST['token'];
	include_once('../php/connection.php');
	include_once('../php/student_data.php');
	
?>
													<h4><i class="menu-icon fa fa-edit"></i> edIT <?php echo $full_name."'s Details"; ?></h4>
															<div class="breadcrumb ace-save-state" id="breadcrumbs">
																				<div  class="" id="sub_nav" >
																				<i class="ace-icon fa fa-edit home-icon"></i><a href="#">   <b>Edit</b></a>
																				<span style="float:right;font-family:;"><a href="#" class=" " name="info" onclick="load_student_info_data(<?php echo $student_info_id;?>)"style="margin-right:20px;">Back</a> </span>
																				</div>
																		</div>
	<h4></h4>
		<div id="add_new_staff_form">
			
			<div class="row" id="phase1" style="display:block">
				<div class="col-lg-6">
						
					  <div class="form-group">
						<label for="first_name">First Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="first_name" value="<?php echo $first_name;?>" placeholder="First Name">
					  </div>
					  <div class="form-group">
						<label for="last_name">Last Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="last_name" value="<?php echo $last_name;?>"  placeholder="Last Name">
					  </div>
					    <div class="form-group">
						<label for="other_name">Other Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="other_name"  value="<?php echo $other_name;?>"  placeholder="Other Name">
					  </div>
					  <div class="form-group">
						<label for="gender">Gender</label>
							<select  class="form-control add_new_staff_form_input" id="gender">
								<option value=''>--select gender--</option>
								<option value='M' <?php echo $option_male; ?>>Male</option>
								<option value='F' <?php echo $option_female; ?>>Female</option>
							</select>
					  </div>
					   <div class="form-group">
						<label for="religion">Religion</label>
							<select  class="form-control add_new_staff_form_input" id="religion">
								<option value=''>--select Religion--</option>
								<option value='Christian' <?php echo $religion_christain; ?>>Christianity</option>
								<option value='Muslim' <?php echo $religion_muslim; ?>>Islam</option>
							</select>
					  </div>
					    <div class="form-group">
						<label for="date_of_birth">Date of Birth</label>
						<input type="date" class="form-control add_new_staff_form_input" id="date_of_birth"  value="<?php echo $date_of_birth;?>"   placeholder="Date of Birth">
					  </div>
					    <div class="form-group">
						<label for="lga">State Of Origin</label>
							<select  class="form-control add_new_staff_form_input" id="state" onchange="load_lga(this.value)">
								<option value=''>--select state--</option>
									<?php 
										echo $options_state;
									?>
							</select>
					  </div>
					  <div class="form-group">
						<label for="lga">Local Government Area (LGA)</label>
							<select  class="form-control add_new_staff_form_input" id="lga">
								<option value=''>--select LGA--</option>
								<?php 
									echo $options_lga;
								?>
							</select>
					  </div>
					 <div class="form-group">
						<label for="tribe">Tribe</label>
						<input type="text" class="form-control add_new_staff_form_input" id="tribe" value="<?php echo $tribe;?>"  placeholder=" ">
					  </div>
					    <div class="form-group">
						<label for="class">Class </label>
							<select  class="form-control add_new_staff_form_input" id="class">
								<option value=''>--select class--</option>
									<?php 
											$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_idd = $class_array['class_id'];
												$class = $class_array['class_name'];
													if($class_id == $class_idd){
														$sl = 'selected';
													}else{
														$sl = '';
													}
														echo '<option value="'.$class_idd.'" '.$sl.'>'.$class.'</option>';
											}
									?>
							</select>
					  </div>
					
					   <div class="form-group">
						<label for="previous_school">Last School Attended</label>
						<input type="text" class="form-control add_new_staff_form_input" id="previous_school" value="<?php echo $previous_school;?>" placeholder="Last School Attended">
					  </div>
					  

					
				</div>
				<div class="col-lg-6">
					 
					  <div class="form-group">
						<label for="reason_for_leaving_the_school">Reason For leaving the school</label>
						<input type="text" class="form-control add_new_staff_form_input" id="reason_for_leaving_the_school" value="<?php echo $reason_for_leaving_the_school;?>" placeholder="Reason For leaving the school">
					  </div>
					  <div class="form-group">
						<label for="email_address">Email Address</label>
						<input type="email" class="form-control add_new_staff_form_input" id="email_address" value="<?php echo $email_address;?>"  placeholder="Email Address">
					  </div>
					  <div class="form-group">
						<label for="phone_number">Phone Number</label>
						<input type="number" class="form-control add_new_staff_form_input" id="phone_number" value="<?php echo $phone_number;?>"  placeholder="Phone Number">
					  </div>
					   
					  <div class="form-group">
						<label for="residential_address">Residential Address</label>
							<textarea id="residential_address" class="form-control add_new_staff_form_input" value="" ><?php echo $residential_address;?></textarea>
					  </div>
					  <div class="form-group">
						<label for="postal_code">Postal Code</label>
						<input type="text" class="form-control add_new_staff_form_input" id="postal_code" value="<?php echo $postal_code;?>"  placeholder="Postal Code">
					  </div>
					 <div class="form-group">
						<label for="guidian_name">Sponsor Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="guidian_name" value="<?php echo $guidian_name;?>"  placeholder="Sponsor Name">
					  </div>
					  <div class="form-group">
						<label for="guidian_phone_number">Sponsor Phone Number</label>
						<input type="text" class="form-control add_new_staff_form_input" id="guidian_phone_number" value="<?php echo $guidian_phone_number;?>"  placeholder="Sponsor Phone Number">
					  </div>
					   <div class="form-group">
						<label for="guidian_other_phone_number">Guidian Other Phone Numbers</label>
						<input type="number" class="form-control add_new_staff_form_input" id="guidian_other_phone_number" value="<?php echo $guidian_other_phone_number;?>"  placeholder="Guidian Other Phone Numbers">
					  </div>
					    <div class="form-group">
						<label for="guadian_relationship">Relationship with Sponsor</label>
						
							<input type="text" class="form-control add_new_staff_form_input" id="guadian_relationship" value="<?php echo $guadian_relationship;?>"  placeholder="Sponsor Occupation">
					  
					  </div>
					   <div class="form-group">
						<label for="guidain_occupation">Sponsor Occupation</label>
						<input type="text" class="form-control add_new_staff_form_input" id="guidain_occupation" value="<?php echo $guidain_occupation;?>"  placeholder="Sponsor Occupation">
					  </div>
					  <div class="form-group">
						<label for="guidian_address">Guidian Residential Address</label>
							<textarea id="guidian_address" class="form-control add_new_staff_form_input" value="" ><?php echo $guidian_address;?></textarea>
					  </div>
					<div class="form-group">
						<button type="button" class="btn btn-primary" onclick="update_student_info(<?php echo $student_info_id; ?>)">Update Details</button>
					  </div>
					  
					
					  
				</div>
			</div>
			
			
		
		</div>
		
		