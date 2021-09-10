<?php
include_once('connection.php');

$sql2 = $conn->query("SELECT * FROM session WHERE status = '1'");
$sql3 = $conn->query("SELECT * FROM term WHERE status = '1'");
$session = '';
$session_id = '';
$term_id = '';
$term = '';
if ($sql2->num_rows > 0 and $sql3->num_rows > 0) {
	$ssm = $sql2->fetch_assoc();
	$tm = $sql3->fetch_assoc();
	$session_id = $ssm['section_id'];
	$session = $ssm['section'];
	$term = $tm['description'];
	$term_id = $tm['id'];
}

$sql_all_class = "select * from classes";

$query_all_class =  mysqli_query($conn, $sql_all_class) or die(mysqli_error($conn));
$num_rows_all_class = mysqli_num_rows($query_all_class);
if ($num_rows_all_class > 0) {
	$sn = 1;
	while ($array_all_class = mysqli_fetch_array($query_all_class)) {
		$class_name = $array_all_class['class_name'];
		$description = $array_all_class['description'];
		$class_id = $array_all_class['class_id'];

		$sql_all_class2 = "select * from student_classes where class_id = '$class_id' and (status = '1' or status='2')  and session_id = '$session_id'";
		$query_all_class2 =  mysqli_query($conn, $sql_all_class2) or die(mysqli_error($conn));
		$num_rows_all_class2 = mysqli_num_rows($query_all_class2);
		if ($sn % 2 == 0) 	$typ = "default";
		else 	$typ = "primary";

		$tr .= '
														
																 	<div class="panel panel-default summary_block" style="background-image:url(../images/summary_bg.png);width:200px;cursor:pointer;border-right:4px solid #B8B8B8">
																	 
																	 <div class="panel-body" onclick="load_all_student_inclass(' . $class_id . ',1)">
																			<h1 style="text-align:right">' . $num_rows_all_class2 . '</h1>
																			<h4 style="text-align:right">' . $class_name . '</h4>
																			<h6><marquee>' . $description . '</marquee></h6>
																	  </div>
																	</div>';
	}
}







?> <h4><i class="menu-icon fa fa-desktop"></i> All STUdents Information</h4>
<div class="breadcrumb ace-save-state" id="breadcrumbs">
	<div class="" id="sub_nav" style="display: flex; justify-content: space-between; flex-wrap: wrap;align-content: center;">
		<div>

			<i class="ace-icon fa fa-home"></i><a href="#"> Home <b></b></a>
		</div>
		<div>

			<button class="btn btn-info" id="add_new_staff_btn" style="" onclick="load_upload_new_student_form()">
				<span class="fa fa-plus-circle">
				</span> upload Students
			</button>
			<button class="btn btn-info" id="upload_new_staff_btn" style="" onclick="load_add_new_student_form()">
				<span class="fa fa-plus-circle">
				</span> Add Student
			</button>
		</div>

	</div>
</div>

<div class="panel panel-default">

	<div class="panel-body text-center" style="background-image:url('../images/world.png');">
		<?php echo $tr; ?>
	</div>
</div>


<section>
	<h2> Manage Promotion</h2>
	<div class="panel panel-default" style="padding: 20px;">
		<div id="promotion_wrap">
		
		
		</div>

		<div class="row">
			<div class="col-md-4">
				<label for="p_session">Promotion Session</label>

				<select name="p_session" id="p_session" class="form-control">
					<option value="">Select Session </option>
					<?php
					$query = mysqli_query($conn, "select * from session where status = '1' ") or die(mysqli_error($conn));
					$row = mysqli_fetch_array($query);
					echo	$session_id = $row['section_id'];
					$session_name = $row['section'];

					$query =  mysqli_query($conn, "select * from session where  section_id < '$session_id'") or die(mysqli_error($conn));
					while ($row = mysqli_fetch_array($query)) {
						$session_id = $row['section_id'];
						$session_name = $row['section'];
						echo '<option value="' . $session_id . '"> ' . $session_name . ' </option>';
					}
					?>

				</select>
			</div>
		
			<div class="col-md-4">
				<label for="p_class">TO Class</label>
				<select name="p_class" id="p_class" class="form-control">
					<option value="">Select Cureent Class </option>
					<?php
					$sql_all_class = "select * from classes";

					$query_all_class =  mysqli_query($conn, $sql_all_class) or die(mysqli_error($conn));
					while ($array_all_class = mysqli_fetch_array($query_all_class)) {
						$class_name = $array_all_class['class_name'];
						$description = $array_all_class['description'];
						$class_id = $array_all_class['class_id'];

						echo '<option value="' . $class_id . '">' . $class_name . ' </option>';
					}

					?>
				</select>
			</div>
		</div>
		<br>
		<br>
		<div class="row">
			<div class="col-md-12">
				<button onclick="load_promote_student_by_session_class()" class="btn btn-lg btn-primary form-control">Promote Students NOW!</button>
			</div>
		</div>

	</div>
</section>