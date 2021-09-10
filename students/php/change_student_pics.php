<?php
	session_start();
?> 
<?php
						include_once('connection.php');
						$document_name = $_FILES["studentpassport"]["name"]; // The file name
						$fileTmpLoc = $_FILES["studentpassport"]["tmp_name"]; // File in the PHP tmp folder
						$filename = 'student_passport'.$_SESSION['student_infoid_4passport'].'.png';
						 $student_info_id = $_SESSION['student_infoid_4passport'];
						 
						 
							if(file_exists('../students_image_upload/'.$filename) == 1){
								unlink('../students_image_upload/'.$filename);
									
							}
						if(!empty($fileTmpLoc)){
								if(move_uploaded_file($fileTmpLoc, '../students_image_upload/'.$filename)){
										
									 // update staff other uploads/
									$update_sql = "update student_info set image_name = '$filename' where student_info_id = '$student_info_id'";
									 $update_query = mysqli_query($conn,$update_sql);
									 if($update_query){
										$upload_err = 'uploaded successfully..'; 
									 }else{
										 $upload_err = 'UPLOAD FAILED....';
									 }
										
								}else{
									 $upload_err = 'OPeratION FAILED....';
								}
						}else{
							$upload_err = ' <span style="color:green">Document  uploaded successfully...</span>'; 
						}
							
						echo $upload_err;
?>