<?php 
		
?>

<div class="breadcrumb ace-save-state" id="breadcrumbs">
	<div  class="" id="sub_nav">
		<button class="btn btn-info" name="info" onclick="load_staff_dashboard()" ><span  class="glyphicon glyphicon-hand-left"  ></span>Back</button>
		<a href="#" > All Staff Information</a>	<i ><span style="font-size:11px;float:center;margin-left:200px;" id="phase_number"> 1 of 3</span> </i>
	</div>
</div>
		<div id="add_new_staff_form">
			
			<div id="phase1" style="display:block">
				<div class="col-lg-6">
					 <div class="form-group">
						<label for="first_name">Title</label>
						<select class="form-control add_new_staff_form_input" id="staff_title">
							<option value="Mr.">Mr.</option>
							<option value="Mrs.">Mrs.</option>
							<option value="Dr.">Dr.</option>
							<option value="Engr.">Engr.</option>
							<option value="Prof.">Prof.</option>
						</select>						
					  </div>

					  <div class="form-group">
						<label for="first_name">First Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="first_name" placeholder="First Name">
					  </div>
					  <div class="form-group">
						<label for="last_name">Last Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="last_name" placeholder="Last Name">
					  </div>
					    <div class="form-group">
						<label for="other_name">Other Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="other_name" placeholder="Other Name">
					  </div>
					  <div class="form-group">
						<label for="gender">Gender</label>
							<select  class="form-control add_new_staff_form_input" id="gender">
								<option value=''>--select gender--</option>
								<option value='M'>Male</option>
								<option value='F'>Female</option>
							</select>
					  </div>
					   <div class="form-group">
						<label for="religion">Religion</label>
							<select  class="form-control add_new_staff_form_input" id="religion">
								<option value=''>--select Religion--</option>
								<option value='Christian'>Christianity</option>
								<option value='Muslim'>Islam</option>
							</select>
					  </div>
					  

					
				</div>
				<div class="col-lg-6">
						
					  <div class="form-group">
						<label for="marital_status">Marital Status</label>
							<select  class="form-control add_new_staff_form_input" id="marital_status">
								<option value=''>--select marital status--</option>
								<option value='single'>Single</option>
								<option value='married'>Married</option>
							</select>
					  </div>
					  <div class="form-group">
						<label for="date_of_birth">Date of Birth</label>
						<input type="date" class="form-control add_new_staff_form_input" id="date_of_birth" placeholder="Date of Birth">
					  </div>
					    <div class="form-group">
						<label for="lga">State Of Origin</label>
							<select  class="form-control add_new_staff_form_input" id="state" onchange="load_lga(this.value)">
								<option value=''>--select state--</option>
									<?php 
											include_once('../php/load_states.php');
									?>
							</select>
					  </div>
					  <div class="form-group">
						<label for="lga">Local Government Area (LGA)</label>
							<select  class="form-control add_new_staff_form_input" id="lga">
								<option value=''>--select LGA--</option>
								
							</select>
					  </div>
					 <div class="form-group">
						<label for="tribe">Tribe</label>
						<input type="text" class="form-control add_new_staff_form_input" id="tribe" placeholder=" ">
					  </div>
					  
					
					  <button  class="btn btn-default" onclick="switch_phases(1,2,2)">Next</button>
				</div>
			</div>
		
			
			
			
			<div  id="phase2" style="display:none">
				<div class="col-lg-6">
						
					  <div class="form-group">
						<label for="email_address">Email Address</label>
						<input type="email" class="form-control add_new_staff_form_input" id="email_address" placeholder="Email Address">
					  </div>
					  <div class="form-group">
						<label for="phone_number">Phone Number</label>
						<input type="number" class="form-control add_new_staff_form_input" id="phone_number" placeholder="Phone Number">
					  </div>
					    <div class="form-group">
						<label for="other_phone_numbers">Other Phone Numbers</label>
						<input type="number" class="form-control add_new_staff_form_input" id="other_phone_numbers" placeholder="Other Phone Numbers">
					  </div>
					  <div class="form-group">
						<label for="residential_address">Residential Address</label>
							<textarea id="residential_address" class="form-control add_new_staff_form_input"></textarea>
					  </div>
					  <div class="form-group">
						<label for="postal_code">Postal Code</label>
						<input type="text" class="form-control add_new_staff_form_input" id="postal_code" placeholder="Postal Code">
					  </div>
					  

					
				</div>
				<div class="col-lg-6">
						
					  <div class="form-group">
						<label for="next_of_kin">Next Of Kin</label>
						<input type="text" class="form-control add_new_staff_form_input" id="next_of_kin" placeholder="Next Of Kin">
					  </div>
					  <div class="form-group">
						<label for="next_of_kin_phone_number">Next Of Kin's Phone Number</label>
						<input type="text" class="form-control add_new_staff_form_input" id="next_of_kin_phone_number" placeholder="Next Of Kin's Phone Number">
					  </div>
					    <div class="form-group">
						<label for="relationship_with_next_of_kin">Relationship with Next of kin</label>
						<select  class="form-control add_new_staff_form_input" id="relationship_with_next_of_kin">
								<option value=''>--Relationship--</option>
								<option value='Brother'>Brother</option>
								<option value='Sister'>Sister</option>
								<option value='Father'>Father</option>
								<option value='Mother'>Mother</option>
								<option value='Son'>Son</option>
								<option value='Daughter'>Daughter</option>
								<option value='Fiance'>Fiance</option>
								<option value='Husband'>Husband</option>
								<option value='Friend'>Friend</option>
							</select>
					  </div>
					  <div class="form-group">
						<label for="next_of_kin_residential_address">Next Of Kin's Residential Address</label>
							<textarea id="next_of_kin_residential_address" class="form-control add_new_staff_form_input"></textarea>
					  </div>
					  <div class="form-group">
						<label for="next_of_kin_postal_code">Postal Code</label>
						<input type="text" class="form-control add_new_staff_form_input" id="next_of_kin_postal_code" placeholder="Postal Code">
					  </div>
					   <button type="submit" class="btn btn-default" onclick="switch_phases(2,1,1)">Back</button>
						<button  class="btn btn-default" onclick="switch_phases(2,3,3)">Next</button>
					
				</div>
			</div>
				<div  id="phase3" style="display:none">
				<div class="col-lg-12">
						
					  <div class="form-group">
						<label for="highest_qualification">Highest Qualification</label>
							<select  class="form-control add_new_staff_form_input" id="highest_qualification">
								<option value=''>--Highest Qualification--</option>
								<option value='B.S.C'>B.S.C</option>
								<option value='HND'>HND</option>
								<option value='ND'>ND</option>
								<option value='PHD'>PHD</option>
								<option value='SSCE'>SSCE</option>
								
							</select>
					  </div>
					  <div class="form-group">
						<label for="school">School</label>
						<input type="text" class="form-control add_new_staff_form_input" id="school" placeholder="School">
					  </div>
					   <div class="form-group">
						<label for="date_obtained">Date Obtained</label>
						<input type="date" class="form-control add_new_staff_form_input" id="date_obtained" placeholder="date_obtained">
					  </div>
					  
					  <div class="form-group">
						<label for="refree">Refree</label>
							<input type="text" class="form-control add_new_staff_form_input" id="refree" placeholder="Refree">
					  </div>
					  <div class="form-group">
						<label for="refree_hone_number">Refree's Phone Number</label>
						<input type="number" class="form-control add_new_staff_form_input" id="refree_hone_number" placeholder="Refree's Phone Number">
					  </div>
					  <div class="form-group" id="">
							<button type="submit" class="btn btn-default" onclick="switch_phases(3,2,2)">Back</button>
						<button type="submit" class="btn btn-default" onclick="switch_phases(3,4,4)">Next</button>
					  </div>
						
					 
				</div>
			</div>
			
			<div  id="phase4" style="display:none">
				<div class="col-lg-12">
						
					 <div class="form-group">
						<label for="section"> Category</label>
	
						<select id="staff_category" class="form-control add_new_staff_form_input">
							<option value="">-- Category --</option>
							<option value="1">Administrator</option>
							<option value="2">Bursar</option>
							<option value="3">Examiner</option>
							<option value="4">Teacher</option>
							<option value="5">Head Master/Mistress</option>
							<option value="6">Principal</option>
							<option value="7">Director</option>														
						</select>
					  </div>
					   
					  

				
				<!--</div>
				<div class="col-lg-6">-->
					 <div class="form-group">
						<label for="section"> Select Section</label>
	
						<select id="section" class="form-control add_new_staff_form_input" onchange="load_classees(this.value);
">
							<option value="">-- Select Section --</option>
							<option value="1">Pry School</option>
							<option value="2">Sec. School</option>
						</select>
					  </div>
						 <div class="form-group">
							<label>Class Teacher ?</label>
							  <input type="radio" id="yes" name="teacher" value="Yes" onchange="make_a_class_teacher()"> Yes
							  <input type="radio" id="no" name="teacher"  value="No" onchange="make_a_class_teacher()" > No
							
						  </div>
						 <div class="form-group" id="teacher_class" style="display:none">
						<label for="class">Class </label>
							<select  class="form-control add_new_staff_form_input" id="class">
								<option value=''>--select class--</option>
									<?php 
											$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												echo '<option value="'.$class_id.'">'.$class.'</option>';
											}
									?>
							</select>
					  </div>
					  <div class="form-group" id="save_staff_output">
					  	 <button type="submit" class="btn btn-default" onclick="switch_phases(4,3,3)">Back</button>
						 	 <button type="submit" class="btn btn-default" onclick="add_new_staff()">ADD Staff</button>
					  </div>
						
					 
				</div>
			</div>
		
		</div>
		
		