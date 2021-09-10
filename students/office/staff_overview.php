<?php
	$staff_info_id = $_POST['token'];
	include_once('../php/staff_data.php');

?>

<div class="myOp" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;border-radius:0px 0px 10px 10px">
															<div class="list-group">
																  <a href="#" class="list-group-item active-list">
																	<h5><?php echo $full_name; ?></h5>
																  </a>
																  <a href="#" class="list-group-item"><h6>First Name : <?php echo $first_name; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Last Name : <?php echo $last_name.' '.$other_name; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Gender : <?php echo $gender; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Religion : <?php echo $religion; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Marital Status : <?php echo $marital_status; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Date of birth : <?php echo $date_of_birth; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Tribe : <?php echo $tribe; ?></h6></a>
																 
																</div>
													</div>
														<div class="myOp" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;border-radius:0px 0px 10px 10px">
															<div class="list-group">
																  <a href="#" class="list-group-item active-list">
																	<h5>Contacts</h5>
																  </a>
																 
																  <a href="#" class="list-group-item"><h6>Phone Number : <?php echo $phone_number; ?></h6></a>
																  <a href="#" class="list-group-item"><h6> Other Phone Number : <?php echo $other_phone_number; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Email address : <?php echo $email_address; ?></h6></a>
																
																 
																</div>
													</div>
														<div class="myOp" style="margin:10px;box-shadow:1px 1px 1px 1px #ccc;border-radius:0px 0px 10px 10px">
															<div class="list-group">
																  <a href="#" class="list-group-item active-list">
																	<h5>Next of kin</h5>
																  </a>
																 
																  <a href="#" class="list-group-item"><h6>Next of kin : <?php echo $next_of_kin; ?></h6></a>
																  <a href="#" class="list-group-item"><h6> Next of kin  Phone Number : <?php echo $next_of_kin_phone_number; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Next of kin Residential address : <?php echo $next_of_kin_residential_address; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Next of kin Relationship : <?php echo $relationship_with_next_of_kin; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Refree : <?php echo $refree; ?></h6></a>
																  <a href="#" class="list-group-item"><h6>Refree Phone Number : <?php echo $refree_hone_number; ?></h6></a>
																
																 
																</div>
													</div>