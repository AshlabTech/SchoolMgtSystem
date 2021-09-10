<?php 
			include_once('connection.php');

		$session = $conn->query("SELECT * FROM session WHERE status = '1'") or die(mysqli_error($conn));
		$term = $conn->query("SELECT * FROM term WHERE status = '1'") or die(mysqli_error($conn));
		if ($session->num_rows>0) {
			if ($term->num_rows>0) {
				$ss1 = $session->fetch_assoc();
				$tt1 = $term->fetch_assoc();
	 			$session_id = $ss1['section_id'];
	 			$term_id = $tt1['id'];
			
			}else{
				echo "term not set";
				exit();
			}	
		}else{
			echo "session not set";
			exit();
		}


	if(isset($_POST['token'])){
		$staff_info_id = $_POST['token'];
	}

$sub_check = "select *,s.id as sid from staff_subjects as s inner join classes as c on c.class_id=s.class_id inner join subject as sb on sb.id=s.subject_id  where s.staff_info_id = '$staff_info_id' and s.status ='1' and s.session_id = '$session_id' and s.term_id='$term_id'";
		$sub_check_run =  mysqli_query($conn,$sub_check) or die(mysqli_error($conn));
		$num_rows_sub_check= mysqli_num_rows($sub_check_run);
		if($num_rows_sub_check > 0){
			$sn = 1;
						echo '<table class="table table-bordered  table-hover" >	
								<tr>
									<td>sn</td>
									<td  class="text-center">Subject</td>
									<td style="text-transform:uppercase">Subject Code</td>											
									<td style="text-transform:uppercase">Class</td>											
									<td style="text-transform:uppercase"></td>											
							</tr>
							<tbody>';
				while($rows = mysqli_fetch_array($sub_check_run)){
						$id = $rows['sid'];
						$subject_id = $rows['subject_id'];
								
				/*				$sql_all_subject= "select * from subject where id = '$subject_id' and status ='1' LIMIT 1";
								$php_process_sql_all_subject =  mysqli_query($conn,$sql_all_subject) or die(mysqli_error($conn));
								*/
									//	$all_subject_array = mysqli_fetch_array($sub_check_run);
													$subject_id = $rows['id'];
													$subject = $rows['subject'];
													$subject_code = $rows['subject_code'];
													$school_section = $rows['school_section'];
													$class_name = $rows['class_name'];

													
													$get_section_name  = mysqli_query($conn,"select * from school_section where school_section_id = '$school_section'") or die(mysqli_error($conn));
													$row = mysqli_fetch_array($get_section_name);
													$section_name = $row['abr'];
												echo '<tr>
													<td>'.$sn.'</td>
													<td width="" class="" style="text-transform:uppercase">'.$subject.'</td>
													<td style="text-transform:uppercase">'.$subject_code.'</td>										
													<td style="text-transform:uppercase">'.$class_name.'</td>										
													<td style="text-transform:uppercase;color:red;font-weight:bold;cursor:pointer" onclick="remove_assigned_subject('.$staff_info_id.','.$id.')">Remove</td>										
											</tr>';
											$sn++;
											$n++;
							
		
				}
				echo '</tbody>
						</table>';
						
		}
			echo '<p><button class="btn btn-primary" style="font-family:Verdana;float:right" onclick="show_subjects_toAssign()"><span class="glyphicon glyphicon-plus-sign"></span>  <b>Assign Subject</b></button></p>
						';
?>