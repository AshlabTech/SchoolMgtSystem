 
 <?php
	include_once('connection.php');
	 $section = mysqli_real_escape_string($conn,$_POST['section']);
	 $payment_description = mysqli_real_escape_string($conn,$_POST['payment_description']);
	 $payment_amount = mysqli_real_escape_string($conn,$_POST['payment_amount']);
	 $gender = mysqli_real_escape_string($conn,$_POST['gender']);
	 $category = mysqli_real_escape_string($conn,$_POST['category']);
			if($category == 1){
				$gender  = 'All';
			}
	 
					// get the current session id
					$query2 = mysqli_query($conn,"select * from session where status = '1' LIMIT 1") or die(mysqli_error($conn));
											$class_array2 = mysqli_fetch_array($query2);
												$session_id = $class_array2['section_id'];
												$session = $class_array2['section'];
											
						// insert into payment details table for new students
						// amount that new students are expected to pay
							$sql_insert_command3 = "insert into payment_details (school_section_id ,current_session_id,sex,payment_description,amount,category)";
							$sql_insert_command3 .= "values ('$section',$session_id,'$gender','$payment_description','$payment_amount','$category')";
							$php_process_sql_query_function3 = mysqli_query($conn,$sql_insert_command3) or die(mysqli_error($conn));
											if($php_process_sql_query_function3){
												echo 1;
											}else{
												echo '
												<label for=""></label><br>
												<button type="submit" class="btn btn-info" onclick="add_payment_details()">Try Again	</button>
												';
											}
											

?>