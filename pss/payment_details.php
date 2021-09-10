 
<!DOCTYPE html>
<html>
	<head>
		<title>Peace Group of Schs. | MOKWA </title>
			
		<link rel="shortcut icon" href="images/e_portal.png">
		
		<link rel="stylesheet" href="../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../css/bootstrap.css">
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../css/styles.css">
		
				
				
				<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../js/jquery-1.10.2.js"></script>
				<script type="text/javascript" src="../js/pss.js"></script>
			
				<script type="text/javascript" src="../js/payment.js"></script>

		
		<style>
			
		</style>
	</head>
<body>
	<!-- body container-->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-1"></div>
				<div class="col-lg-10"  id="whole_body">
				
				<!--	header -->
					<?php include_once('header.php'); ?>
				<!--	end header -->
				
				
				<!--	Download Registration Form or Fill Form Online -->
					 	<div class="row">
								<div class="col-lg-12" style="padding-top:20px"> 
								<blockquote style="font-size:12px">
									<p>Below are the payment details for both new and old students in all the sections. You can as well register online or download our registration form </p>
									
									<button class="btn btn-primary">Download Reg Form</button>
									<button class="btn btn-info" onclick="load_application_form()">Apply Online</button>
								</blockquote>
									
								
								</div>
						</div>
				
				<!--	Download Registration Form or Fill Form Online -->
				
				
				<div class="row">
								<div class="col-lg-12" id="payment_wrap"> 	<?php include_once('laod_payment_details.php'); ?></div>
						</div>

	</div>
	<div class="col-lg-1"></div>
	</div>
	
	
	<div id="abdul_android_pageOverlay"></div>
	<div id="abdul_android_alertBox">
		<div id="abdul_android_boxContent"></div>
		
	</div>
	
	
	