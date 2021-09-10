<?php
	session_start();
?> 
<?php
						include_once('connection.php');
						$document_name = $_FILES["staffpassport"]["name"]; // The file name
						$fileTmpLoc = $_FILES["staffpassport"]["tmp_name"]; // File in the PHP tmp folder
						$filename = 'staff_passport'.$_SESSION['staff_infoid_4passport'].'.png';
						 $staff_info_id = $_SESSION['staff_infoid_4passport'];
					if(file_exists('../staff_image_uploads/'.$filename) == 1){
								unlink('../staff_image_uploads/'.$filename);
									
					}else{

					}
					
					$allowedExts = array("gif", "jpeg", "jpg", "png");
						$temp = explode(".", $_FILES["staffpassport"]["name"]);
						$extension = end($temp);
						if ((($_FILES["staffpassport"]["type"] == "image/gif")
						|| ($_FILES["staffpassport"]["type"] == "image/jpeg")
						|| ($_FILES["staffpassport"]["type"] == "image/jpg")
						|| ($_FILES["staffpassport"]["type"] == "image/pjpeg")
						|| ($_FILES["staffpassport"]["type"] == "image/x-png")
						|| ($_FILES["staffpassport"]["type"] == "image/png"))
						&& in_array($extension, $allowedExts))
						  {
												if(!empty($fileTmpLoc)){
															$move_file = move_uploaded_file($fileTmpLoc, '../staff_image_uploads/'.$filename);
															if($move_file){
																	
																 // update staff other uploads/
																$update_sql = "update staff_info set image_name = '$filename' where staff_info_id = '$staff_info_id'";
																 $update_query = mysqli_query($conn,$update_sql);
																 if($update_query){
																	$upload_err = '../staff_image_uploads/'.$filename; 
																
																 }else{
																	 $upload_err = '2';
																	
																 }
																	
															}else{
																 $upload_err = '2';
															}
													}else{
															$update_sql = "update staff_info set image_name = '' where staff_info_id = '$staff_info_id'";
																 $update_query = mysqli_query($conn,$update_sql);
																 if($update_query){
																	$upload_err = '../staff_image_uploads/default.jpg'; 
																	unset($_FILES["staffpassport"]["tmp_name"]);
																	
																 }else{
																	 $upload_err = '2';
																	
																 }
													}
										
						  }
						else
						  {
						  $upload_err =  "Invalid file";
						  }
						
							
						echo $upload_err;
?>