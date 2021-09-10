<?php session_start(); 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['PSS_STUDENT_LOGIN_ID'])) {

	$student_login_id = $_SESSION['PSS_STUDENT_LOGIN_ID'];
///	include_once('../../administration/php/connection.php');

date_default_timezone_set('Africa/Lagos');


$servername = "localhost";
$username = "elmaasu1_user";
$password = "_DU=yAUqdfuV";

$db = "elmaasu1_db";

//create login connection and login
$conn =  mysqli_connect($servername, $username, $password, $db) or die(mysqli_error($conn));


$Fschool = mysqli_query($conn, "SELECT * FROM school LIMIT 1") or die(mysqli_error($conn));
$school = mysqli_fetch_array($Fschool);
$school_name = $school['name'];
$school_address = $school['address'];
$school_abbr = $school['abbr'];
$school_image = $school['image'];



define('DB_HOST', $servername);
define('DB_NAME', $db);
define('DB_USER', $username);
define('DB_PASS', $password);

$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	include_once('../../administration/php/objects.php');
} else {
	header('location:../?token=1');
}

$student_info_id = $student_login_id;
$student_ = $student_obj->find($student_info_id);
//GET term ID
$term_query = mysqli_query($conn, "select * from term where status = '1'") or die(mysqli_error($conn));
$term_array = mysqli_fetch_array($term_query);
$current_term_id = $term_array['term'];

//get current session id
$get_current_session_id_query = mysqli_query($conn, "select * from session where status = '1' ") or die(mysqli_error($conn));
$get_current_session_id_arr = mysqli_fetch_array($get_current_session_id_query);
$session_id = $get_current_session_id_arr['section_id'];


$getStudentClasses = $student_obj->getStudentClasses($student_info_id);

$error_message = '';
if(isset($_POST['view-result'])){
	$class_id = $_POST['class_id'];
	$term_id = $_POST['term_id'];
	$code = $_POST['pin'];

	$code_info = $result_obj->isCodeValid(array($code,$class_id));

	if(!empty($code_info)){
		
		if($code_info->class_id == $class_id){
				$isCodeUsed = $result_obj->isCodeUsed($code);
				if(!empty($isCodeUsed)){
					if($isCodeUsed->student_id != $student_info_id){
						$error_message = 'This ['.$code.'] has already been used by another student. ';
					}else{
						$save = $result_obj->updateResultCode([$isCodeUsed->life + 1,$student_info_id, $class_id,$code,$term_id]);
					}
				}else{
					$save = $result_obj->saveResultCode([ $student_info_id, $class_id,$code,$term_id, $session_id,1,1]);
				}


				if(empty($error_message)){
					
						$class_ = $class_obj->find($class_id);

						$session_ =  $session_obj->find($session_id);
						$term_ =  $term_obj->find($term_id);

						$session_full = $session_->section;
						$term_full = $term_->description;
						$student_ = $student_obj->find($student_info_id);

						$subjects = $subject_obj->get(['school_section' => $class_->school_section_id]);


						$getStudentTotalScore = $mark_obj->getStudentTotalScore(array($student_info_id, $session_id, $term_id, $class_id));
						$totalNumberInClass = count($student_obj->getAllStudentByClass($class_id, $session_id));

						include_once('../../administration/office/_view_student_result.php');
						exit;
				}
		}else{
			$error_message = 'This ['.$code.'] is meant for different class';
		}
	}else{
		$error_message = 'This ['.$code.'] is wrong';
	}
}
?>



<!DOCTYPE html>
<html>

<head>
	<title> Elmaasum Student Portal </title>

	<link rel="shortcut icon" href="../../images/e_portal.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../../css/bootstrap.css">
	<link rel="stylesheet" href="../../css/bootstrap.min.css">
	<link rel="stylesheet" href="../../css/bootstrap-theme.css">
	<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">


	<!-- inlcude all javascript files -->
	<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="../../js/bootstrap.js"></script>
	<script type="text/javascript" src="../../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/admin_script.js"></script>
	<script type="text/javascript" src="../js/admin_script2.js"></script>
	<script type="text/javascript" src="../js/admin_reg_script.js"></script>
	<script type="text/javascript" src="js/staff_scripts.js"></script>

	<style>

	</style>

</head>

<body style="">

	<?php include_once('header.php'); ?>

	<div class="row" style="margin:0;">
		<div class="col-lg-2 myOp" style="margin:0;padding:0">
			<div style="margin:0">
				<center><img src="../../images/e_portal.png" style="width:60px;height:60px;margin-top:10px"></center>
				<ul class="nav nav-list" style="margin-top:10px">
					<li class="active option_btn" onmouseup="load_staff_dashboard()">
						<a href="#"><i class="menu-icon fa fa-tachometer"></i><span class="menu-text"> Dashboard </span></a>
						<a href="results.php"><i class="menu-icon fa fa-file"></i><span class="menu-text"> Results </span></a>
						<b class="arrow"></b>
					</li>

					<li class="option_btn" onclick="window.location.href='../php/logout.php'">

						<i class="menu-icon glyphicon glyphicon-off"></i>
						<span class="menu-text" style="color:red"> Logout </span>
						<b class="arrow"></b>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-lg-8" id="display_content">
			<h4>Welcome , <b><?= $student_->first_name; ?></b></h4>
			<div class="alert alert-info"> </div>
			<hr />
			<section>
				<form action="results.php" method="POST">
				<p></p>
					<div class="row">
						<div class="col-md-4 col-lg-4">
							<p>
							
								<select name="class_id" class="form-control" required>
								<?php
						
									foreach ($getStudentClasses as $key => $class_) {
										$class_info = $class_obj->find($class_->class_id);
										$class_name = $class_info->class_name;
										$class_id = $class_info->class_id;
										echo '<option value="' . $class_id . '">' . $class_name . '</option>';
									}
									?>
								</select>
							</p>

						</div>
						<div class="col-md-4 col-lg-4">
							<p>
								<select name="term_id" class="form-control" required>
									<?php
										
										echo '<option value="1">First TERM</option>';
										if($current_term_id >= 2){
											echo '<option value="2">Second TERM</option>';
										}

										if($current_term_id ==3){
											echo '<option value="3">Third TERM</option>';
										}

									?>

								</select>
							</p>
						</div>
						<div class="col-md-4 col-lg-4"><input type="text" required="" name="pin" class="form-control" placeholder="Result Pin" required></div>
					</div>
					<p class="text-center"><button class="btn btn-lg btn-primary" type="submit" name="view-result">Check Result</button></p>
					<?php
						if(!empty($error_message)){
							echo '<div class="alert alert-danger">'.$error_message.'</div>';
						}
					?>
					<div style="width:100%;display:block;margin:4px 10px;">

					</div>
				</form>

			</section>


		</div>
		<div class="col-lg-2 text-center" id="events">
			<?php include_once('pages/date_time.php'); ?>


		</div>
	</div>



	<!-- body container-->
	<div id="pageOverlay"></div>
	<div id="alertBox">
		<div id="alertBoxContent"></div>
	</div>
	<div id="abdul_android_pageOverlay"></div>
	<div id="abdul_android_alertBox">
		<div id="abdul_android_boxContent"></div>

	</div>

</body>

</html>