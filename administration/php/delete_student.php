<?php
	include_once('connection.php');
		$student_info_id = $_POST['token'];
						
											
													$sql_delete_command4 = "update  student_classes set status = '0'  where student_info_id = '$student_info_id'";
													$php_process_sql_query_function4 = mysqli_query($conn,$sql_delete_command4) or die(mysqli_error($conn));
													
													$sql_delete_command5 = "update  student_info set status = '0'  where student_info_id = '$student_info_id'";
													$php_process_sql_query_function5 = mysqli_query($conn,$sql_delete_command5) or die(mysqli_error($conn));
														
														if($php_process_sql_query_function4 and $php_process_sql_query_function5)
															echo 'Deleted successfully....';
														else
															echo 'Operation FAILED....';
												
											
											

?>