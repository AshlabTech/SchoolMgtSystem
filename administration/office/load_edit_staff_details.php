<?php
	$staff_info_id = $_POST['token'];
	include_once('../php/staff_data.php');

?>
<div id="add_edit_staff_form">
<div class="" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;border-radius:0px 0px 10px 10px">
<div class="list-group">
	  <a href="#" class="list-group-item active-list">
		<h5><?php echo $full_name; ?></h5>
	  </a>
	  <a href="#" class="list-group-item"><h6>First Name : <input type="text" placeholder="first_name" name="first_name" id="first_name" value="<?php echo $first_name; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Last Name :  <input type="text" placeholder="last_name" name="last_name"id="last_name" value="<?php echo $last_name; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Other Names :  <input type="text" placeholder="other_name" name="other_name"id="other_name" value="<?php echo $other_name; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Gender :  <input type="text" placeholder="gender" name="gender"id="gender" value="<?php echo $gender; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Religion :  <input type="text" placeholder="religion"name="religion" id="religion" value="<?php echo $religion; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Marital Status :  <input type="text" placeholder="marital_status" name="marital_status"id="marital_status" value="<?php echo $marital_status; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Date of birth :  <input type="text" placeholder="date_of_birth" name="date_of_birth" id="date_of_birth" value="<?php echo $date_of_birth; ?>"/></h6></a>
	  <a href="#" class="list-group-item"><h6>Tribe :  <input type="text" placeholder="tribe" id="tribe" name="tribe" value="<?php echo $tribe; ?>"></h6></a>
	 
	</div>
</div>
<div class="" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;border-radius:0px 0px 10px 10px">
<div class="list-group">
	  <a href="#" class="list-group-item active-list">
		<h5>Contacts</h5>
	  </a>
	 
	  <a href="#" class="list-group-item"><h6>Phone Number : <input type="text" placeholder="phone_number" name="phone_number"id="phone_number" value="<?php echo $phone_number; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6> Other Phone Number : <input type="text" placeholder="other_phone_number" name="other_phone_number" id="other_phone_number" value="<?php echo $other_phone_number; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6>Email address : <input type="text" placeholder="email_address"  name="email_address" id="email_address" value="<?php echo $email_address; ?>"></h6></a>
	
	 
	</div>
</div>
<div class="" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;border-radius:0px 0px 10px 10px">
<div class="list-group">
	  <a href="#" class="list-group-item active-list">
		<h5>Next of kin</h5>
	  </a>
	 
	  <a href="#" class="list-group-item"><h6>Next of kin : <input type="text" placeholder="next_of_kin" name="next_of_kin" id="next_of_kin" value="<?php echo $next_of_kin; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6> Next of kin  Phone Number : <input type="text" placeholder="next_of_kin_phone_number" name="next_of_kin_phone_number" id="next_of_kin_phone_number" value="<?php echo $next_of_kin_phone_number; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6>Next of kin Residential address : <input type="text" placeholder="next_of_kin_residential_address" name="next_of_kin_residential_address" id="next_of_kin_residential_address" value="<?php echo $next_of_kin_residential_address; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6>Next of kin Relationship : <input type="text" placeholder="relationship_with_next_of_kin" name="relationship_with_next_of_kin" id="relationship_with_next_of_kin" value="<?php echo $relationship_with_next_of_kin; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6>Refree : <input type="text" placeholder="refree" name="refree" id="refree" value="<?php echo $refree; ?>"></h6></a>
	  <a href="#" class="list-group-item"><h6>Refree Phone Number : <input type="text" placeholder="refree_hone_number" name="refree_hone_number" id="refree_hone_number" value="<?php echo $refree_hone_number; ?>"></h6></a>
	
	 
	</div>
	
	<div class="list-group">
	  <a href="#" class="list-group-item active-list">
		<h5>School Detiails</h5>
	  </a>
	 
	  <a href="#" class="list-group-item"><h6>School Section : <select id="section" class="form-control add_new_staff_form_input" onchange="load_classees(this.value);">
							<option value="">-- Select Section --</option>
							<option value="1" <?= ($staff_section == 1 ? 'selected' : '')?>>Pry School</option>
							<option value="2" <?= ($staff_section == 2 ? 'selected' : '')?>>Sec. School</option>
						</select></h6></a> 
	  <a href="#" class="list-group-item"><h6> Role : <select id="staff_category" class="form-control add_new_staff_form_input">
							<option value="">-- Category --</option>
							<option value="1" <?= ($staff_type == 1 ? 'selected' : '')?>>Administrator</option>
							<option value="2" <?= ($staff_type == 2 ? 'selected' : '')?>>Bursar</option>
							<option value="3" <?= ($staff_type == 3 ? 'selected' : '')?>>Examiner</option>
							<option value="4" <?= ($staff_type == 4 ? 'selected' : '')?>>Teacher</option>
							<option value="5" <?= ($staff_type == 5 ? 'selected' : '')?>>Head Master/Mistress</option>
							<option value="6" <?= ($staff_type == 6 ? 'selected' : '')?>>Principal</option>
							<option value="7" <?= ($staff_type == 7 ? 'selected' : '')?>>Director</option>														
						</select></h6></a>
	  <a href="#" class="list-group-item"><h6> : 
						 <div class="form-group" id="teacher_class" style="display:block">
						<label for="class">Class Teacher of: </label>
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
	  </h6></a>

	 
	</div>
	
	
	<div class="text-center"><button class="btn btn-lg btn-primary" onclick="upate_staff_details(<?= $staff_info_id;?>)"> Update Details</button></div>
	<hr/>
	<div class="form-group" id="save_staff_output"></div>
	<hr/>
</div>
</div>