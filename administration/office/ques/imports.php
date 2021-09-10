<?php
//include("../php/dbconnect.php");
include_once('../../php/connection.php');

if ($_GET['type']='imports') {

	
	//$student_info_id;
	$check_new_class_id;
	$get_current_class_id;
	$fetch_current_id_row;
	

	//$type     = $_GET['type'];
	$sucess1 =0;	
	//$c_class_id;
	$selected = $_GET['sel'];	
	$n_class_id = $_GET['b'];	
	$term_id     = $_GET['t'];	
	$session_id  = $_GET['s'];
	$output = '';// either success
	$output1 = ''; // student exist in other class
	$output2 = '';// no payment found
	//$output3 = '';// already in that class
	$students = '';

		for ($i=0; $i < sizeof($selected); $i++) { 
			$student_info_id = $selected[$i];
		$check_if_student_exist = $conn->query("SELECT s.adm_no FROM student_classes as c INNER JOIN student_info as s ON s.student_info_id= c.student_info_id WHERE c.student_info_id='$student_info_id' AND c.session_id = '$session_id' AND c.term_id='$term_id' AND c.status != '0'") OR die(mysqli_error($conn));	
//		echo './'; 	
		if ($check_if_student_exist->num_rows > 0){
			$r = $check_if_student_exist->fetch_assoc();
			$students .= $r['adm_no'].', ';
	
			$output1 = 1;//student exist in other classes	
			//return '#string';
		}else{



		
		// get student section nur,pry,jss or ss
							$sql_student_section = "select * from classes where class_id = '$n_class_id' and status = '1'";
							$query_student_section=  mysqli_query($conn,$sql_student_section) or die(mysqli_error($conn));
							$num_rows_sql_student_section = mysqli_num_rows($query_student_section);		
								if($num_rows_sql_student_section > 0){
									$class_section = mysqli_fetch_array($query_student_section);
									$school_section_id = $class_section['school_section_id'];
									$class_description = $class_section['description'];
								}else{
									echo 'No Response 2';
										exit();
								}									

								// get due payment for the session	i.e how much is the student expected to pay		
								$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
									$sql_sum_amount .= " and school_section_id = '$school_section_id'   and status = '1' and category =1";
									$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
									$sum_row = mysqli_fetch_row($query_sum_amount);		
									 $total_amount = $sum_row[0];


			if($total_amount > 0){
					$date = date('Y-m-d');
					if($n_class_id <= 15){
						//$sql = "insert into student_classes(student_info_id, class_id, session_id, school_fees, date_promoted_enrolled, status, last_date_modified,term_id ) values('".$student_info_id."','".$n_class_id."','".$session_id."','".$total_amount."','".$date."','1','".$date."', $term_id)";
						$run =	mysqli_query($conn,"insert into student_classes(student_info_id, class_id, session_id, school_fees, date_promoted_enrolled, status, last_date_modified, term_id ) values('$student_info_id','$n_class_id','$session_id','$total_amount','$date','1','$date', '$term_id')") or die(mysqli_error($conn).' line 111');
						//echo $sql;
								if ($run) {
									$output =  1;
									//echo "string";
									# code...
								}

					}else{
						$update_student_class = mysqli_query($conn,"update student_classes set status ='3' where student_info_id = '$student_info_id'") or die(mysqli_error($conn).' line 131');
						$output = 1;
					}
			}else{
				$output2 =  2; /// No payment definition is gotten
			}
		}										 
			
			mysqli_close($conn);
			if ($output2 == 1) {
				echo 'payment not defined for that class';
			}else if($output == '' AND $output1 == 1) {
				echo 'All Selected Student Already exist in other class';
			}else if($output == 1 AND $output1 == 1) {
				echo 'Some Selected Student ('.rtrim($students, ',').') Already exist in other class';
			}else if($output == 1 AND $output1 == '' ){
				echo 'successfully added';
			}
			//echo $output.'-'.$output1.'-'.$output2;
		
	}

}
?>