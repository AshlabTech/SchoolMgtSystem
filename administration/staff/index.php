<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../? token=2');
	}
	include_once('../php/staff_data.php');

?>



<!DOCTYPE html>
<html>
	<head>
		<title> Staff Portal </title>
			
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
												<?php include_once('pages/staff_navs.php'); ?>
											</div>
											<div class="col-lg-8" id="display_content">
															
												<script>
													load_staff_dashboard(<?php echo $staff_info_id; ?>);
												</script>
											</div>
											<div class="col-lg-2" id="events">
												<?php include_once('pages/date_time.php'); ?>
												<?php include_once('pages/online_staff.php'); ?>				
																
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
