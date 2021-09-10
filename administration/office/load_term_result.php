<?php session_start(); ?>

<?php
		
		
	if(isset($_SESSION['staff_info_id'])){
		$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../');
	} 
	if(!isset($_POST['result_session'])){
			exit();
	}

		include_once('../php/connection.php');
		
		$result_session;
		$result_class;
		$result_subject;
		$result_term;
		$heading;
		$students_rows;
		
		$result_session = mysqli_real_escape_string($conn,$_POST['result_session']);
		$result_class = mysqli_real_escape_string($conn,$_POST['result_class']);
		//$result_subject = mysqli_real_escape_string($conn,$_POST['result_subject']);
		 $result_term = mysqli_real_escape_string($conn,$_POST['result_term']);
		
		//get session
				$session_query = mysqli_query($conn,"select * from session where  section_id = '$result_session'") or die(mysqli_error($conn));
				$session_array = mysqli_fetch_array($session_query);
				$session_id = $session_array['section_id'];
			    $session = $session_array['section'];
				
		//get class 
			$class_section= "select * from classes where class_id = '$result_class' and status ='1'";
			$class_section_run =  mysqli_query($conn,$class_section) or die(mysqli_error($conn));
			$class_section_num_rows =  mysqli_num_rows($class_section_run);
				if($class_section_num_rows > 0){
					$section_id_arr = mysqli_fetch_array($class_section_run);
					$section_id = $section_id_arr['school_section_id'];
					$class_name = $section_id_arr['class_name'];
				}else{
					echo 'class not found... try again';
				}
				
				
				if($result_term ==1)
				{
					$term = 'FIRST TERM';
				}
				else if($result_term ==2)
				{
					$term = 'SECOND TERM';
				}
				else{
					$term = 'THIRD TERM';		
				}
					
				?>
									<h2 class="text-center"><?= $class_name.' '.$term.' RESULT';?></h2>
									<h4 class="text-center text-danger"><?= $session.' ACADEMIC SESSION';?></h4>
									<h4 class="text-left text-primary" onclick="back_manage_student_result()"><span class="glyphicon glyphicon-arrow-left"><span> Back</h4>

									<hr>
									<table class="table" border="2">
									<tr>
										<th class="text-center">SN</th>
										<th v>IMAGE</th>
										<th class="text-center">NAME</th>
										<th class="text-center">ACTIONS</th>
									</tr>
				<?php
				
				$sql_all_class = "select * from student_classes where class_id = '$result_class' and (status = '1' or status = '2') AND session_id = '$session_id' ORDER BY student_class_id ";
				$query_all_class =  mysqli_query($conn,$sql_all_class) or die(mysqli_error($conn));
					$num_rows_all_class = mysqli_num_rows($query_all_class);
					if($num_rows_all_class > 0){
						
						$sn = 1;
						while($array_all_class = mysqli_fetch_array($query_all_class)){
							$student_info_id = $array_all_class['student_info_id'];
													
								$sql_student_info = "select * from student_info where student_info_id = '$student_info_id'";
								$query_student_info =  mysqli_query($conn,$sql_student_info) or die(mysqli_error($conn));
								$num_rows_student_info = mysqli_num_rows($query_student_info);		
									
									if($num_rows_student_info > 0){
											$arrray_student_info = mysqli_fetch_array($query_student_info);
											$first_name = $arrray_student_info['first_name'];
											$last_name = $arrray_student_info['last_name'];
											$other_name = $arrray_student_info['other_name'];
											$gender = $arrray_student_info['gender'];
											$image_name = $arrray_student_info['image_name'];
											
											if(empty($image_name) or !file_exists('../students_image_upload/'.$image_name)){
												if($gender=='F')
												{
													$image_name = 'default_F.jpg';
												}
												else
												{
													$image_name = 'default.jpg';
												}
													
											}
											
											//get full_name
										$full_name = $first_name.' '.$other_name.' '.$last_name;
										$hash_id = $student_info_id;
										$token=$student_info_id.','.$result_session.','.$result_class.','.$result_term;
								?>
									<tr>

											<td><?= $sn;?> </td>
											<td> <img class="media-object img img-circle" src="../students_image_upload/<?= $image_name;?>" style="width:30px;height:30px" alt="$full_name"></td>
											<td class="text-left"><?= strtoupper($full_name); ?> </td>
											<td>
											<button class="btn btn primary"><i class="fa fa-comment"></i></button>
											<a href="view_student_result.php?token=<?= $student_info_id.'&cl='.$result_class.'&se='.$result_session.'&tr='.$result_term;?>" target="_BLANK"><button class="btn btn-info"> <i class="fa fa-print"></i></button></a></td>
									</tr>
										
									<?php
									$sn++;
									}
									else{
										$students_rows .='no record found..';
									}
									
						}
					}else{
						$students_rows .='NO RESULT....';
					}
					?>
					</table>

					<?php
		
		mysqli_close($conn);
	
?>
