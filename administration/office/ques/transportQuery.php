<?php
//include("../php/dbconnect.php");
include_once('../../php/connection.php');
if($_GET['type']== 'transferS'){

	
	
	//$student_info_id;
	$check_new_class_id;
	$get_current_class_id;
	$fetch_current_id_row;
	

	//$type     = $_GET['type'];
	$sucess1 =0;	
	$c_class_id;
	$selected = explode(',',rtrim($_GET['select'],','));	
	$n_class_id = $_GET['newclass'];	
	//$term_id     = $_GET['term'];	
	/*$session_id  = $_GET['session'];*/
	/*echo '-'.$term_id.'-'.$session_id;*/

//		$student_info_id = $_POST['token'];
		
	//get current session id
	$get_current_term = $conn->query("SELECT * FROM term WHERE status ='1'");
	if ($get_current_term->num_rows>0) {
		$termn = $get_current_term->fetch_assoc();
		$term_id = $termn['id'];
	}else{
		echo "please set term";
		die();
	}

		$query2 = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
		$class_array2 = mysqli_fetch_array($query2);
		$session_id = $class_array2['section_id'];

		//get current class id
	foreach ($selected as $student_info_id) {

		$check_if_student_exist = $conn->query("SELECT * FROM student_classes WHERE student_info_id='$student_info_id' AND session_id = '$session_id' AND term_id='$term_id'") OR die(mysqli_error($conn));
		$chk_pass = false;
		if ($check_if_student_exist->num_rows > 0) {
			$remove_from_student_classes = $conn->query("UPDATE student_classes SET status ='0' WHERE student_info_id='$student_info_id' AND session_id = '$session_id' AND term_id='$term_id'") OR die(mysqli_error($conn));	 
			$chk_pass = true;		
		}
		
		


		$get_current_class_id = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' and (status = '1' or status = '2')") or die(mysqli_error($conn));
		$fetch_current_id_row = mysqli_fetch_array($get_current_class_id);
		$c_class_id = $fetch_current_id_row['class_id'];
		
		
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
					if($n_class_id <= 18){
						$check_new_class_id = mysqli_query($conn,"select * from student_classes where student_info_id = '$student_info_id' and session_id = '$session_id' and status !='0'") or die(mysqli_error($conn));
						
							if(mysqli_num_rows($check_new_class_id) >0){

								$row = mysqli_fetch_assoc($check_new_class_id);	
								$student_class_id = $row['student_class_id'];
								if($row['class_id'] == $n_class_id){
									$output =  3;  ///// trying to transfeer to current existing class
									
								}else{
									////// remove from current classs

							$conn->query("UPDATE student_classes SET status= '0' WHERE student_class_id = $student_class_id  ") OR die(mysqli_error($conn));

									/// insert new record, say register into new class
									mysqli_query($conn,"insert into student_classes(student_info_id,class_id,session_id,school_fees,date_promoted_enrolled,status,last_date_modified)values('$student_info_id','$n_class_id','$session_id','$total_amount',now(),'1',now())") or die(mysqli_error($conn));
										$output =  1;
								}
									

								
							
								
							}else{
								
								$update_student_class = mysqli_query($conn,"update student_classes set status ='3' where student_info_id = '$student_info_id'") or die(mysqli_error($conn));
								$change_student_class = mysqli_query($conn,"insert into student_classes(student_info_id,class_id,session_id,school_fees,date_promoted_enrolled,status,last_date_modified)values('$student_info_id','$n_class_id','$session_id','$total_amount',now(),'1',now())") or die(mysqli_error($conn));
								
									if($change_student_class and $update_student_class){
										$output = 1;
									}
									else{
										$output =  0;
									}
							}
					}else{
						$update_student_class = mysqli_query($conn,"update student_classes set status ='3' where student_info_id = '$student_info_id'") or die(mysqli_error($conn));
						$output = 1;
					}
			}else{
				$output =  2; /// No payment definition is gotten
			}
													 
			
			
		
	}

	mysqli_close($conn);
			echo $output;
}



?>