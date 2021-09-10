<img src="../../images/ajax-loader.gif" id="loaderI">
<?php 
/*var first_name = getId('first_name').value;
	var last_name = getId('last_name').value;
	var other_name = getId('other_name').value;
	var gender = getId('gender').value;
	var religion = getId('religion').value;
	var date_of_birth = getId('date_of_birth').value;
	var state = getId('state').value;
	var lga = getId('lga').value;
	var tribe = getId('tribe').value;
	var student_class = getId('class').value;
	//var student_passport = getId('student_passport').value;
	var previous_school = getId('previous_school').value;
	var reason_for_leaving_the_school = getId('reason_for_leaving_the_school').value;
	var email_address = getId('email_address').value;
	var phone_number = getId('phone_number').value;
	var guidian_other_phone_number = getId('guidian_other_phone_number').value;
	var residential_address = getId('residential_address').value;
	var postal_code = getId('postal_code').value;	
	var guidian_name = getId('guidian_name').value;
	var guidian_phone_number = getId('guidian_phone_number').value;
	var guadian_relationship = getId('guadian_relationship').value;
	var guidain_occupation = getId('guidain_occupation').value;
	var guidian_address = getId('guidian_address').value;
	var student_type = getId('student_type').value;*/

	//include_once('../php/load_states.php');
	include_once('../php/connection.php');	
	require_once('../../assets/spreadsheet-reader/php-excel-reader/excel_reader2.php');
	require_once('../../assets/spreadsheet-reader/SpreadsheetReader.php');
if (isset($_POST["import"]))
{      
  $allowedFileType = ['application/vnd.ms-excel','text/xls','text/xlsx','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
if(in_array($_FILES["file"]["type"],$allowedFileType)){
  	$success =0;
  	$error =0;
  	$existaddNo ='';


        $targetPath = 'uploads/'.$_FILES['file']['name'];
        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
        
        $Reader = new SpreadsheetReader($targetPath);
        
        $sheetCount = count($Reader->sheets());
        
        for($i=0;$i<$sheetCount;$i++)
        {
            $Reader->ChangeSheet($i);
            $num = -1;
            foreach ($Reader as $Row)
            {
            	$num++;
            	if($num== 0){
            	}else{
          		
          		 $class_id = "";
                if(isset($Row[0])) {
                    $class_id = mysqli_real_escape_string($conn,$Row[0]);
                }

                $addNo = "";
                if(isset($Row[1])) {
                    $addNo = mysqli_real_escape_string($conn,$Row[1]);
                }
                $first_name = "";
                if(isset($Row[2])) {
                    $first_name = mysqli_real_escape_string($conn,$Row[2]);
                }

                $last_name = "";
                if(isset($Row[3])) {
                    $last_name = mysqli_real_escape_string($conn,$Row[3]);
                }
                $other_name = "";
                if(isset($Row[4])) {
                    $other_name = mysqli_real_escape_string($conn,$Row[4]);
                }
                $gender = "";
                if(isset($Row[5])) {
                    $gender = mysqli_real_escape_string($conn,$Row[5]);
                }
                 $religion = "";
                if(isset($Row[6])) {
                    $religion = mysqli_real_escape_string($conn,$Row[6]);
                }
                 $date_of_birth = "";
                if(isset($Row[7])) {
                    $date_of_birth = mysqli_real_escape_string($conn,$Row[7]);
                }
                $phone_number = "";
                if(isset($Row[8])) {
                    $phone_number = mysqli_real_escape_string($conn,$Row[8]);
                }
                $residential_address = "";
                if(isset($Row[9])) {
                    $residential_address = mysqli_real_escape_string($conn,$Row[9]);
                }
                $guidian_name = "";
                if(isset($Row[10])) {
                    $guidian_name = mysqli_real_escape_string($conn,$Row[10]);
                }
                $guidian_address = "";
                if(isset($Row[11])) {
                    $guidian_address = mysqli_real_escape_string($conn,$Row[11]);
                }
                $guidian_phone_number = "";
                if(isset($Row[12])) {
                    $guidian_phone_number = mysqli_real_escape_string($conn,$Row[12]);
                }
                $guidian_other_phone_number = "";
                if(isset($Row[13])) {
                    $guidian_other_phone_number = mysqli_real_escape_string($conn,$Row[13]);
                }
                $guidain_occupation = "";
                if(isset($Row[14])) {
                    $guidain_occupation = mysqli_real_escape_string($conn,$Row[14]);
                }                
                $guadian_relationship = "";
                if(isset($Row[15])) {
                    $guadian_relationship = mysqli_real_escape_string($conn,$Row[15]);
                }
                $tribe = "";
                if(isset($Row[16])) {
                    $tribe = mysqli_real_escape_string($conn,$Row[16]);
                }
                $email_address = "";
                if(isset($Row[17])) {
                    $email_address = mysqli_real_escape_string($conn,$Row[17]);
                }
                 $postal_code = "";
                if(isset($Row[18])) {
                    $postal_code = mysqli_real_escape_string($conn,$Row[18]);
                }
                 $previous_school = "";
                if(isset($Row[19])) {
                    $previous_school = mysqli_real_escape_string($conn,$Row[19]);
                }
                $reason_for_leaving_the_school = "";
                if(isset($Row[20])) {
                    $reason_for_leaving_the_school = mysqli_real_escape_string($conn,$Row[20]);
                }
                if (!empty($first_name) AND !empty($last_name) AND !empty($gender) AND !empty($addNo) AND !empty($class_id)) {
                	$run = $conn->query("SELECT * FROM student_info WHERE adm_no='$addNo'");
 					if($run->num_rows<1){
	                    $admitted_year = date('y');
						$password = base64_encode(md5($school_abbr).md5('1234'));
						//get current session id
						$query2 = mysqli_query($conn,"select * from session where status = '1' ") or die(mysqli_error($conn));
						$class_array2 = mysqli_fetch_array($query2);
						$session_id = $class_array2['section_id'];
									
						//GET term ID
						$term_query = mysqli_query($conn,"select * from term where status = '1'") or die(mysqli_error($conn));
						$term_array = mysqli_fetch_array($term_query);
						$term_id = $term_array['term'];
										
						// get student section nur,pry,jss or ss
						$sql_student_section = "select * from classes where class_id = '$class_id' and status = '1'";
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

						$no_query = mysqli_query($conn,"SELECT * FROM student_info") or die(mysqli_error($conn));
						$no = mysqli_num_rows($no_query);
						$no = IntVal($no) + 1;
						
						$no = strlen($no) == 1 ? '000'.$no : $no;
						$no = strlen($no) == 2 ? '00'.$no : $no;
						$no = strlen($no) == 3 ? '0'.$no : $no;
					    $student_no = $school_abbr.'/'.$admitted_year.'/'.$no;
					    $student_type = 1;
						if($student_type ==2 or $student_type =='2'){
							// get due payment for the session	i.e how much is the student expected to pay		
							$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
							$sql_sum_amount .= " and school_section_id = '$school_section_id'  and sex = '$gender' and status = '1' and category =2 ";
							$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
							$sum_amount_rows =  mysqli_num_rows($query_sum_amount);
							$sum_row = mysqli_fetch_row($query_sum_amount);		
							$total_amount = $sum_row[0];
						}else if($student_type ==1 or $student_type =='1'){
							// get due payment for the session	i.e how much is the student expected to pay		
							$sql_sum_amount = "select  SUM(amount) from payment_details where current_session_id = '$session_id' ";
							$sql_sum_amount .= " and school_section_id = '$school_section_id'   and status = '1' and category =1";
							$query_sum_amount =  mysqli_query($conn,$sql_sum_amount) or die(mysqli_error($conn));
							$sum_amount_rows =  mysqli_num_rows($query_sum_amount);
							$sum_row = mysqli_fetch_row($query_sum_amount);		
							 $total_amount = $sum_row[0];
						}

						if(Intval($total_amount) <= 0){
							echo '<b style="color:red">Sorry, we can\'t  proceeed as payment has not been defined...</select>';
							exit();
						}
						$state_id = null;
						$lga_id = null;						
						//insert into the 
						$sql_insert_command = "insert into student_info ( 
						adm_no,
						first_name,
						last_name,
						other_name,
						gender,
						religion,
						date_of_birth,
						state_id,
						lga_id,
						 tribe,
						 email_address,
						 phone_number,
						 guidian_other_phone_number,
						 residential_address,
						 postal_code,
						 guidian_name,
						 guidian_phone_number,
						 guadian_relationship,
						 guidian_address,
						 previous_school,
						 reason_for_leaving_the_school,
						 guidain_occupation,
						 date_enrolled,
						 status,
						 admitted_year)

						 values (
						 '$addNo', 
						 '$first_name',
						 '$last_name',
						 '$other_name',
						 '$gender',
						 '$religion',
						 '$date_of_birth',
						 '$state_id',
						 '$lga_id',
						 '$tribe',
						 '$email_address',
						 '$phone_number',
						 '$guidian_other_phone_number',
						 '$residential_address',
						 '$postal_code',
						 '$guidian_name',
						 '$guidian_phone_number',
						 '$guadian_relationship',
						 '$guidian_address',
						 '$previous_school',
						 '$reason_for_leaving_the_school',
						 '$guidain_occupation',
						 now(),
						 '$student_type',
						 ' $admitted_year')";
						$php_process_sql_query_function = mysqli_query($conn,$sql_insert_command) or die(mysqli_error($conn));
						if($php_process_sql_query_function){
							//get the staff id from the student_info table
							/*$sql_insert_command2 = "select MAX(student_info_id) from student_info";
							$php_process_sql_query_function2 = mysqli_query($conn,$sql_insert_command2) or die(mysqli_error($conn));
							$student_info_id_array = mysqli_fetch_row($php_process_sql_query_function2);*/
							$student_info_id = mysqli_insert_id($conn);
								
							$sql_insert_command3 = "insert into  student_classes (student_info_id,class_id,session_id,term_id,school_fees,date_promoted_enrolled,status)";
							$sql_insert_command3 .= "values ('$student_info_id','$class_id','$session_id','$term_id','$total_amount',now(),'$student_type')";
							$php_process_sql_query_function3 = mysqli_query($conn,$sql_insert_command3) or die(mysqli_error($conn));
							
							// login details
							$sql_insert_command4 = "insert into student_login_info (student_id,student_no,password)";
							$sql_insert_command4 .= "values ('$student_info_id','$student_no','$password')";
							$php_process_sql_query_function4 = mysqli_query($conn,$sql_insert_command4) or die(mysqli_error($conn));
							if($php_process_sql_query_function3){
								$success = 1;								
							}
							else{
								echo 'student is not added....';
							}
						}else{
							ECHO 'OPERATION FAILED...';
						}
								
					}else{
					 	$existaddNo .= $addNo.',';
					}
					$success = 1;

                }else{
                	$error =1;
                }               
            }
            	
             }
             if($success== 1 AND $error == 0){
             	if(!empty($existaddNo)){         
             		$type = "book" ;
             			$message = "<div class='alert alert-warning' style='font-family:arial; font-size:1em;text-align:center;'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Warning! </strong> &nbsp Not All Student Upload successfully<br> This admission numbers (". rtrim($existaddNo,',').") already exists</div>
             			";
             		//echo '<script type="text/javascript">window.location="student.php?act=06&action=upload";</script>';
             	}else{
             		$type = "book" ;
					$message = "<div class='alert alert-success' style='font-family:arial; font-size:1em;text-align:center;'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success! </strong> All Student Upload successfully. please follow the System formart</div>
					";
             	}
             }else if($success== 0 AND $error == 1){
             	$type = "book" ;
             	$message ="<div class='alert alert-danger' style='font-family:arial; font-size:1em;text-align:center;'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong> Student Could Not Upload successfully. please follow the System formart</div>
             	";
             	//echo '<script type="text/javascript">window.location="student.php?act=6";</script>';
             }else if($success== 1 AND $error == 1){
             	$type = "book" ;
             	$message = "<div class='alert alert-warning' style='font-family:arial; font-size:1em;text-align:center;'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>Success!</strong>Not All Student Upload successfully. please follow the System formart</div>";
             	//echo '<script type="text/javascript">window.location="student.php?act=16";</script>';
             }
        
         }
  }
  else
  { 
        $type = "error";
        $message = "Invalid File Type. Upload Excel File.";
  }
}


?>
	<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/styles.css">
				<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>

<div style="display: flex; flex-wrap: wrap;align-content: center;margin-bottom: 10px; border: 1px solid #ccc;padding: 10px;">	
	<label for="class" style="padding: 5px 5px 0px 0px;">Get Class id</label>		
	<select title="select class to get class id" class="form-control add_new_staff_form_input" id="classi" onchange="(function(){ $('#classID').html($('#classi').val())})()" name="class" required="">
	<option value=''>--select class--</option>
		<?php 
			$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
			while($class_array = mysqli_fetch_array($query)){
					$class_id = $class_array['class_id'];
					$class = $class_array['class_name'];
					echo '<option value="'.$class_id.'">'.$class.'</option>';
				}
		?>
	</select>
	<div id="classID" style="padding: 5px"></div>
</div>
<form method="POST" enctype="multipart/form-data" target="_SELF" style="width: 30%;">
						
<!-- 	    <div class="form-group">
		<label for="class">Class </label>
			<select  class="form-control add_new_staff_form_input" id="classi" name="class" required="">
				<option value='' selected>--select class--</option>
					<?php 
						/*$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
						while($class_array = mysqli_fetch_array($query)){
								$class_id = $class_array['class_id'];
								$class = $class_array['class_name'];
								echo '<option value="'.$class_id.'">'.$class.'</option>';
							}*/
					?>
			</select>
	  </div>	 -->	
	  <div class="form-group">
	  	<label for="upload">select file</label>
	  	<input type="file" id="upload" name="file"  class="form-control" accept="xls" id="filetoupload" required="" >
	  </div>	
	  <input class="btn btn-primary" type="submit" value="upload Students" name="import">						
	
	</form>
	<?php
	$show = 0;
	if (isset($type)) {
		if ($type == "error") {
			$show = 1;			
		}else{ $show = 2;}
	}
		if ($show ==1) {
			?>
		 <div class="alert alert-danger"><?php echo $message; ?></div> 
			<?php
		}
		if ($show == 2) {
			echo $message;
		}

	?>
<script type="text/javascript">
	$('#loaderI').hide();
</script>
