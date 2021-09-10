<?php session_start(); ?>

<?php
		$query2;
		$query;
		$class;
		$class_id;
		$class_query;
		$sql_all_subject;
		$session;
		$session_id;
		$subject;
		$subject_code;
		$subject_id;
		$school_section;
		$class_array2;
		$staff_info_id;
		$all_subject_array;
		$php_process_sql_all_subject;
		
	if(isset($_SESSION['staff_info_id'])){
		$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../');
	} 
	if(!isset($_POST['token'])){
			exit();
	}

		include_once('../php/connection.php');
?>

<section id="exam_section" class="text-center">
	<div class="exams_block">
		<div class="icon"><img  class="img img-circle" src="../images/book.png"></div>
		<div  class="content" onclick="manage_students_result()"><h3>Result</h3></div>
	</div>
	
	<div class="exams_block">
		<div class="icon"><img  class="img img-circle" src="../images/cap.png"></div>
		<div  class="content"><h3>Examination</h3></div>
	</div>
	
	<div class="exams_block">
		<div class="icon"><img  class="img img-circle" src="../images/book.png"></div>
		<div class="content"><h3>Examination</h3></div>
	</div>
</section>


<section id="exam_section_2" style="display:none"class="text-center">
	<div class="exams_block">
		<div class="icon"><img  class="img img-circle" src="../images/book.png"></div>
		<div  class="content" onclick="manage_students_result()">
			<h4 style="margin-top:40px;">Session</h4>
			<p>
					<select class="form-control" id="result_session">
										
										<?php 
											$query2 = mysqli_query($conn,"select * from session order by status desc ") or die(mysqli_error($conn));
											while($class_array2 = mysqli_fetch_array($query2)){
												$session_id = $class_array2['section_id'];
												$session = $class_array2['section'];
												
												echo '<option value="'.$session_id.'">'.$session.'</option>';
												
												
										
											}
									?>
					</select>
			</p>
		</div>
	</div>
	
	<div class="exams_block">
		<div class="icon"><img  class="img img-circle" src="../images/cap.png"></div>
		<div  class="content">
			<h4 style="margin-top:40px;">Class</h4>
			<p>
					<select class="form-control" id="result_class" onchange="(this.value)">
										<?php 
											$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												
													echo '<option value="'.$class_id.'" >'.$class.'</option>';
											}
									?>
						</select>
					</p>
			</div>
	</div>
	
	
	<div class="exams_block">
		<div class="icon"><img  class="img img-circle" src="../images/book.png"></div>
		<div class="content" >
			<h4 style="margin-top:40px;">Term</h4>
			<p>
					<select class="form-control" id="result_term" onchange="">
						
						<option value="1">First Term</option>
						<option value="2">Second Term</option>
						<option value="3">Third Term</option>
					</select>
					</p>
		</div>
	</div>
		<section class="text-center" style="margin-top:20px;">
			<button class="btn btn-lg btn-info" onclick="load_term_result()"> <span class="glyphicon glyphicon-import"> </span>Load Results </button>
		</section>
</section>

<section class="text-center" id="display_result">
			
</section>
