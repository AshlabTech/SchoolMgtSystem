<?php 
			include_once('../php/connection.php');
?>

	<h4 class="text-center">New Student Registration Form </h4>
		<div id="">
					
					 
			<div class="row" id="" style="display:block">
				<div class="col-lg-6">
						
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
					    <div class="form-group">
						<label for="date_of_birth">Date of Birth</label>
						<input type="date" class="form-control add_new_staff_form_input" id="date_of_birth" placeholder="Date of Birth">
					  </div>
					    <div class="form-group">
						<label for="lga">State Of Origin</label>
							<select  class="form-control add_new_staff_form_input" id="state" onchange="load_lga(this.value)">
								<option value=''>--select state--</option>
									<?php 
											include_once('../administration/php/load_states.php');
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
					    <div class="form-group">
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
					
					   <div class="form-group">
						<label for="previous_school">Last School Attended</label>
						<input type="text" class="form-control add_new_staff_form_input" id="previous_school" placeholder="Last School Attended">
					  </div>
					
				</div>
				<div class="col-lg-6">
					 
					  <div class="form-group">
						<label for="reason_for_leaving_the_school">Reason For leaving the school</label>
						<input type="text" class="form-control add_new_staff_form_input" id="reason_for_leaving_the_school" placeholder="Reason For leaving the school">
					  </div>
					  <div class="form-group">
						<label for="email_address">Email Address</label>
						<input type="email" class="form-control add_new_staff_form_input" id="email_address" placeholder="Email Address">
					 </div>
					  <div class="form-group">
						<label for="phone_number">Phone Number</label>
						<input type="number" class="form-control add_new_staff_form_input" id="phone_number" placeholder="Phone Number">
					  </div>
					   
					  <div class="form-group">
						<label for="residential_address">Residential Address</label>
							<textarea id="residential_address" class="form-control add_new_staff_form_input"></textarea>
					  </div>
					  <div class="form-group">
						<label for="postal_code">Postal Code</label>
						<input type="text" class="form-control add_new_staff_form_input" id="postal_code" placeholder="Postal Code">
					  </div>
					 <div class="form-group">
						<label for="guidian_name">Sponsor Name</label>
						<input type="text" class="form-control add_new_staff_form_input" id="guidian_name" placeholder="Sponsor Name">
					  </div>
					  <div class="form-group">
						<label for="guidian_phone_number">Sponsor Phone Number</label>
						<input type="text" class="form-control add_new_staff_form_input" id="guidian_phone_number" placeholder="Sponsor Phone Number">
					  </div>
					   <div class="form-group">
						<label for="	guidian_other_phone_number">Guidian Other Phone Numbers</label>
						<input type="number" class="form-control add_new_staff_form_input" id="guidian_other_phone_number" placeholder="Guidian Other Phone Numbers">
					  </div>
					    <div class="form-group">
						<label for="guadian_relationship">Relationship with Sponsor</label>
						<select  class="form-control add_new_staff_form_input" id="guadian_relationship">
								<option value=''>--Relationship--</option>
								<option value='Brother'>Brother</option>
								<option value='Sister'>Sister</option>
								<option value='Father'>Father</option>
								<option value='Mother'>Mother</option>
								<option value='Friend'>Friend</option>
								<option value='other'>Other</option>
							</select>
					  </div>
					   <div class="form-group">
						<label for="guidain_occupation">Sponsor Occupation</label>
						<input type="text" class="form-control add_new_staff_form_input" id="guidain_occupation" placeholder="Sponsor Occupation">
					  </div>
					  <div class="form-group">
						<label for="guidian_address">Guidian Residential Address</label>
							<textarea id="guidian_address" class="form-control add_new_staff_form_input"></textarea>
					  </div>
					<div class="form-group">
					<div id="std_reg_ouput">
					</div>
						<button type="button" class="btn btn-primary" onclick="save_new_student()">Add Students</button>
					  </div>
					  
					
					  
				</div>
			</div>
			
			<div class="alert ">
					<dl>
						<dt><P><b>NOTE the following :</b></P></dt>
						<dd>
						<P>1. please ensure a valid email address,because we will send you a comfirmatory message</P>
						<P>2. please ensure a valid email address,because we will send you a comfirmatory message</P>
						<P>3. please ensure a valid email address,because we will send you a comfirmatory message</P>
						<P>4. please ensure a valid email address,because we will send you a comfirmatory message</P>
						<P>5. please ensure a valid email address,because we will send you a comfirmatory message</P>
						</dd>
					<dl>
					</div>
		
		</div>
		
		