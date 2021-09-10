<?php 
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
session_start(); ?>
<?php
	if(isset($_SESSION['PSS_STUDENT_LOGIN_ID'])){
		
			$student_login_id = $_SESSION['PSS_STUDENT_LOGIN_ID'];
			include_once('php/connection.php');
	}
	else{
		header('location:../');
	}
	$student_info_id = $student_login_id;
	include_once('../php/student_data.php');
	
	//GET term ID
	$term_query = mysqli_query($conn,"select * from term where status = '1'") or die(mysqli_error($conn));
	$term_array = mysqli_fetch_array($term_query);
	$term_id = $term_array['term'];
?>



<!DOCTYPE html>
<html>
	<head>
		<title> Elmaasum Student Portal </title>
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
	   <meta name="viewport" content="width=device-width, initial-scale=1" >
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
						<div style="margin:0" >
						
								<center><img src="../../images/e_portal.png" style="width:60px;height:60px;margin-top:10px"></center>
								
								<ul class="nav nav-list" style="margin-top:10px">
											<li class="active option_btn" onmouseup="load_staff_dashboard()">
											<a href="#"><i class="menu-icon fa fa-tachometer"></i><span class="menu-text">     Dashboard </span></a>
											<a href="results.php"><i class="menu-icon fa fa-file"></i><span class="menu-text">     Results </span></a>
											<b class="arrow"></b>
											</li>
											
											
											<li class="option_btn"  onclick="window.location.href='php/logout.php'">
											<a href="#">
												<i class="menu-icon glyphicon glyphicon-off"></i>
												<span class="menu-text" style="color:red" onclick="window.location.assign('../php/logout.php')"> Logout </span>
											</a>
											<b class="arrow"></b>
										</li>
									</ul>
							
								
								
						</div>
					</div>
					<div class="col-lg-8" >
						<div class="alert alert-success"><h4> Welcome <?= $last_name;?></h4></div>
					<div id="display_content">
							
						<script>
							load_staff_dashboard(1);
						</script>
					</div>
					</div>
					<div class="col-lg-2" id="events">
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
