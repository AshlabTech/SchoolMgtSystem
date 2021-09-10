

<?php session_start(); ?>
<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
	if(isset($_SESSION['staff_info_id'])){
		$type = $_SESSION['type'];
			/*if($type != 1){
				header('location:../staff');
			}*/
			$staff_info_id = $_SESSION['staff_info_id'];
	}
	else{
		header('location:../?token=1');
	}
	include_once('../php/staff_data.php');
?>


<!DOCTYPE html>
<html>
	<head>
		<title>ELMAASUM ACADEMY. | PORTAL </title>
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	   
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/styles.css">
		<link href="../js/listjs/PagingStyle.css" rel="stylesheet" />		
		<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
		 <script src="../js/sweetalert.js"></script>
		 <script src="../js/listjs/paging.js"></script>
		 <script src="../js/datatable.js"></script>
		 <!-- <script src="../js/datatable.css"></script> -->
		
		<style>
			
		</style>

	</head>
<body>
	
			<!-- end of header -->
							<!-- beginning of header -->
<!-- BEginning of nav -->
				<nav class="navbar " style='border-radius:0px;background-color:#880E4F;border:none;margin:0'>
				  <div class="container-fluid">
					<div class="navbar-header">
					  <a class="navbar-brand" href="#" style="color:#fff">
						<?php echo $school_abbr; ?>
					  </a>
					</div>
					 <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  
					<form class="navbar-form navbar-left" role="search">
						<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
						</div>
					
					</form>
					
					</div><!-- /.navbar-collapse -->
				  </div>
				</nav>
					
			<!-- end of nav -->
		
			<!-- body container-->
		
	
		<div class="row" style="margin:0">
			<div class="col-md-12" style="padding:0">
				<ul class="nav nav-tabs" role="tablist" id="myTab" style="background-color:#f1f1f1;height:20px"></ul>
			</div>
		</div>
			<!-- end of header -->	
			<!-- body container-->
		<div class="container-fluid">
			<section>
				<div  id="whole_body" style="padding:0;">
						<?php include_once('home.php');?>
										
				</div>
			</section>
			
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
				<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../js/admin_script.js"></script>
				<script type="text/javascript" src="../js/admin_script2.js"></script>
				<script type="text/javascript" src="../js/admin_script3.js"></script>
				<script type="text/javascript" src="../js/admin_reg_script.js"></script>
				
				<!-- <script type="text/javascript" src="../js/timing.js"></script> -->
</body>
</html>
