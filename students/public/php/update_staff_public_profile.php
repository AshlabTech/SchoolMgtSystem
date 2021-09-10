 
 <?php
	include_once('connection.php');
	 $staff_info_id = mysqli_real_escape_string($conn,$_POST['token']);
	 $first_name = mysqli_real_escape_string($conn,$_POST['first_name']);
	 $last_name = mysqli_real_escape_string($conn,$_POST['last_name']);
	 $other_name = mysqli_real_escape_string($conn,$_POST['other_name']);
	 $religion = mysqli_real_escape_string($conn,$_POST['religion']);
	 $marital_status = mysqli_real_escape_string($conn,$_POST['marital_status']);
	 $date_of_birth = mysqli_real_escape_string($conn,$_POST['date_of_birth']);
	 $state_id = mysqli_real_escape_string($conn,$_POST['state']);
	 $lga_id = mysqli_real_escape_string($conn,$_POST['lga']);
	 $tribe = mysqli_real_escape_string($conn,$_POST['tribe']);
	 $email_address = mysqli_real_escape_string($conn,$_POST['email_address']);
	 $phone_number = mysqli_real_escape_string($conn,$_POST['phone_number']);
	 $other_phone_numbers = mysqli_real_escape_string($conn,$_POST['other_phone_numbers']);
	 $face_book_name = mysqli_real_escape_string($conn,$_POST['face_book_name']);
	 
		$update_sql  = "UPDATE staff_info SET first_name='$first_name',last_name='$last_name',other_name='$other_name',date_of_birth='$date_of_birth',religion='$religion',marital_status='$marital_status',state_id ='$state_id',lga_id='$lga_id',tribe= '$tribe',email_address='$email_address',phone_number='$phone_number',other_phone_number='$other_phone_numbers'";
		$update_sql .= "WHERE staff_info_id = '$staff_info_id'";
		$update_query = mysqli_query($conn,$update_sql) or die(mysqli_error($conn));
		
		$update_sql_social  = "UPDATE staff_socials SET face_book_name = '$face_book_name' WHERE staff_info_id = '$staff_info_id'";
		$update_query_social = mysqli_query($conn,$update_sql_social) or die(mysqli_error($conn));
		
			if($update_query ){
				echo 1;
				
			}else{
				echo '<b style="color:red">Your personal profile is not updated sussessfully...</b>';
			}
	
	mysqli_close($conn);
	exit();
?>