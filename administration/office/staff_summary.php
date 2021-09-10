<?php 
			include_once('../php/connection.php');
					//get the staff id from the staff_info table
				//get the staff id from the staff_info table
				$sql_all_staff = "select * from staff_info where status='1'";
				$php_process_sql_all_staff =  mysqli_query($conn,$sql_all_staff) or die(mysqli_error($conn));
				$total_number_of_staff = mysqli_num_rows($php_process_sql_all_staff);
				
				$sql_all_staff22 = "select * from student_info where status  ='1' or status='2'";
				$php_process_sql_all_staff22 =  mysqli_query($conn,$sql_all_staff22) or die(mysqli_error($conn));
				$total_students = mysqli_num_rows($php_process_sql_all_staff22);
				
				$sql_all_staff2 = "select * from staff_info where highest_qualification = 'B.S.C' and status='1'";
				$php_process_sql_all_staff2 =  mysqli_query($conn,$sql_all_staff2) or die(mysqli_error($conn));
				$total_number_of_degree_staff = mysqli_num_rows($php_process_sql_all_staff2);
				
				$sql_all_staff3 = "select * from staff_info where section = '1' and status='1'";
				$php_process_sql_all_staff3 =  mysqli_query($conn,$sql_all_staff3) or die(mysqli_error($conn));
				$total_number_of_pry = mysqli_num_rows($php_process_sql_all_staff3);
				
				$sql_all_staff4 = "select * from staff_info where section = '2' and status='1'";
				$php_process_sql_all_staff4 =  mysqli_query($conn,$sql_all_staff4) or die(mysqli_error($conn));
				$total_number_of_sec = mysqli_num_rows($php_process_sql_all_staff4);
				
				$sql_all_staff5 = "select * from staff_info where highest_qualification = 'SSCE' and status='1'";
				$php_process_sql_all_staff5 =  mysqli_query($conn,$sql_all_staff5) or die(mysqli_error($conn));
				$total_number_of_ssce = mysqli_num_rows($php_process_sql_all_staff5);
				
				$sql_all_staff6 = "select * from staff_info where highest_qualification = 'PHD' and status='1'";
				$php_process_sql_all_staff6 =  mysqli_query($conn,$sql_all_staff6) or die(mysqli_error($conn));
				$total_number_of_phd = mysqli_num_rows($php_process_sql_all_staff6);
				
				$total_number_of_indegin_staff;
				$total_number_of_degree_staff;
				
				

?>					
		
		
	