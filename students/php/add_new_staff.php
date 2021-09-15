 
 <?php
	include_once('connection.php');
	
	 $first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
	 $last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
	 $other_name = mysqli_real_escape_string($conn,$_POST['other_name']);
	 $gender = mysqli_real_escape_string($conn,$_POST['gender']);
	 $religion = mysqli_real_escape_string($conn,$_POST['religion']);
	 $marital_status = mysqli_real_escape_string($conn,$_POST['marital_status']);
	 $date_of_birth = mysqli_real_escape_string($conn,$_POST['date_of_birth']);
	 $state_id = mysqli_real_escape_string($conn,$_POST['state']);
	 $lga_id = mysqli_real_escape_string($conn,$_POST['lga']);
	 $tribe = mysqli_real_escape_string($conn,$_POST['tribe']);
	 $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
	 $phone_number = mysqli_real_escape_string($conn,$_POST['phone_number']);
	 $other_phone_numbers = mysqli_real_escape_string($conn,$_POST['other_phone_numbers']);
	 $residential_address = mysqli_real_escape_string($conn,$_POST['residential_address']);
	 $postal_code = mysqli_real_escape_string($conn,$_POST['postal_code']);
	 $section = mysqli_real_escape_string($conn,$_POST['section']);
	 $teacher_class = mysqli_real_escape_string($conn,$_POST['teacher_class']);
	 $staff_category = mysqli_real_escape_string($conn,$_POST['staff_category']);
	 
	 	 //nexk of kin
	 $next_of_kin = mysqli_real_escape_string($conn,$_POST['next_of_kin']);
	 $next_of_kin_phone_number = mysqli_real_escape_string($conn,$_POST['next_of_kin_phone_number']);
	 $relationship_with_next_of_kin = mysqli_real_escape_string($conn,$_POST['relationship_with_next_of_kin']);
	 $next_of_kin_residential_address = mysqli_real_escape_string($conn,$_POST['next_of_kin_residential_address']);
	 $next_of_kin_postal_code = mysqli_real_escape_string($conn,$_POST['next_of_kin_postal_code']);
	 	 
	//highest qualifications
	 $highest_qualification = mysqli_real_escape_string($conn,$_POST['highest_qualification']);
	 $school = mysqli_real_escape_string($conn,$_POST['school']);
	 $date_obtained = mysqli_real_escape_string($conn,$_POST['date_obtained']);
	 $refree = mysqli_real_escape_string($conn,$_POST['refree']);
	 $refree_hone_number = mysqli_real_escape_string($conn,$_POST['refree_hone_number']);
	 
	 
	 
	 // staff login details
	 
	 $staff_login_id = @date('y').@date('m').@date('d').(@date('h')-1).@date('i').@date('s'); 
	 if($staff_category == 1){
			
			$no_query = mysqli_query($conn,"SELECT * FROM staff_info WHERE staff_type = 1 ") or die(mysqli_error($conn));
			$no = mysqli_num_rows($no_query);
			$no = IntVal($no) + 1;
			$no = strlen($no) == 1 ? '000'.$no : $no;
			$no = strlen($no) == 2 ? '00'.$no : $no;
			$no = strlen($no) == 3 ? '0'.$no : $no;
		$staff_number = 'PSS/ADMIN/'.$no;
	 }else{
		 $no_query = mysqli_query($conn,"SELECT * FROM staff_info WHERE staff_type != 1 ") or die(mysqli_error($conn));
			$no = mysqli_num_rows($no_query);
			$no = IntVal($no) + 1;
			
			$no = strlen($no) == 1 ? '000'.$no : $no;
			$no = strlen($no) == 2 ? '00'.$no : $no;
			$no = strlen($no) == 3 ? '0'.$no : $no;
		$staff_number = 'PSS/STAFF/'.$no;
	 }
	 $staff_type = IntVal($staff_category);
	 $password = md5($school_abbr.'1234');
	 
	 
	 //check if email_address already exist
			$email_check = mysqli_query($conn,"SELECT * FROM staff_info WHERE email_address = '$email_address'") or die(mysqli_error($conn));
			$email_check_num_rows = mysqli_num_rows($email_check);
		
			if($email_check_num_rows <= 0 )
				{
					//insert into the 
							$sql_insert_command = "insert into staff_info (first_name,last_name,other_name,gender,religion,marital_status,date_of_birth,state_id,lga_id,staff_number,staff_type,";
							$sql_insert_command .= "tribe,email_address,phone_number,other_phone_number,residential_address,postal_code,next_of_kin,next_of_kin_phone_number,";
							$sql_insert_command .= "relationship_with_next_of_kin,next_of_kin_residential_address,next_of_kin_postal_code,highest_qualification,school,";
							$sql_insert_command .= "date_obtained,refree,refree_hone_number,section,date_staff_employed)";
							$sql_insert_command .= "values ('$first_name','$last_name','$other_name','$gender','$religion','$marital_status','$date_of_birth','$state_id','$lga_id','$staff_number','$staff_type',";
							$sql_insert_command .= "'$tribe','$email_address','$phone_number','$other_phone_numbers','$residential_address','$postal_code','$next_of_kin','$next_of_kin_phone_number',";
							$sql_insert_command .= "'$relationship_with_next_of_kin','$next_of_kin_residential_address','$next_of_kin_postal_code','$highest_qualification','$school',";
							$sql_insert_command .= "'$date_obtained','$refree','$refree_hone_number','$section',now())";
							$php_process_sql_query_function = mysqli_query($conn,$sql_insert_command) or die(mysqli_error($conn));
								
								//get the staff id from the staff_info table
								$sql_insert_command2 = "select MAX(staff_info_id) from staff_info";
								$php_process_sql_query_function2 = mysqli_query($conn,$sql_insert_command2) or die(mysqli_error($conn));
								$staff_info_id_array = mysqli_fetch_row($php_process_sql_query_function2);
								$staff_info_id = $staff_info_id_array[0];
									
											//staff login details
											$sql_insert_command3 = "insert into staff_login_info (staff_info_id,staff_login_id,password,type)";
											$sql_insert_command3 .= "values ('$staff_info_id','$staff_number','$password','$staff_category')";
											$php_process_sql_query_function3 = mysqli_query($conn,$sql_insert_command3) or die(mysqli_error($conn));
											// staff bank details
											$bank_details_sql = "insert into staff_bank_details (staff_info_id,account_name,account_number,	account_bvn,sort_code,account_type,bank)";
											$bank_details_sql .= "values ('$staff_info_id','','','','','','')";
											$bank_details_query = mysqli_query($conn,$bank_details_sql) or die(mysqli_error($conn));
											// staff socials site details
											$socials_sql = "insert into staff_socials (staff_info_id,face_book_name)";
											$socials_sql .= "values ('$staff_info_id','')";
											$socials_query	 = mysqli_query($conn,$socials_sql) or die(mysqli_error($conn));
											
															if($php_process_sql_query_function3 and $bank_details_query and $socials_query){
																
																if($teacher_class !=''){
																	//get session id 
																		$query2 = mysqli_query($conn,"select * from session where status = '1'") or die(mysqli_error($conn));
																		while($class_array2 = mysqli_fetch_array($query2)){
																			$session_id = $class_array2['section_id'];
																			$session = $class_array2['section'];
																		}
																		$insert_query = mysqli_query($conn,"insert into staff_classes(staff_info_id,class_id,session_id) values ('$staff_info_id','$teacher_class','$session_id')") or die(mysqli_error($conn));
										
																	}
																echo '<div class="alert alert-success" role="alert">
																			<button type="button" class="close" data-dismiss="alert" aria-label="Close">  <span aria-hidden="true">&times;</span></button>
																			<b>'.$first_name.' is added to the system successfully...</b>
																	</div>';
															
																
															}
															else{
																echo '<div class="alert alert-warning" role="alert">
																			<button type="button" class="close" data-dismiss="alert" aria-label="Close">  <span aria-hidden="true">&times;</span></button>
																			<b>Oops. Something went wrong.'.mysqli_error($conn).'</b>
																	</div><br><button type="submit" class="btn btn-default" onclick="add_new_staff()">Try again</button>';
															
															}
				}else{
					echo '<b style="color:red">Email Address Already Exist</b>';
				}
	 	

?>