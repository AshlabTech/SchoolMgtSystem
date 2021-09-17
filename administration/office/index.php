<?php session_start(); ?>
<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
	if(isset($_SESSION['staff_info_id'])){
	 	$type = $_SESSION['type'];
			if($type == 1){
			//	header('location:../staff');
			}
			$staff_info_id = $_SESSION['staff_info_id'];
		
	}
	else{
	    header('location:../?logout');
	  
	}
	include_once('../php/staff_data.php');
?>


<!DOCTYPE html>
<html>
	<head>
		<title>ELMAASUM </title>
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	   
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<!-- <link rel="stylesheet" href="../../css/bootstrap.min.css"> -->
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../js/datatable.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link href="../js/listjs/PagingStyle.css" rel="stylesheet" />		
		<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
		<script src="../js/jquery3.js"></script>
		
		<script src="../js/sweetalert.js"></script>
		<script src="../js/listjs/paging.js"></script>
		<script src="../js/datatable.js"></script>
		<script src="../js/bootstrap.js"></script>
		<script src="../Login_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
		<script src="../js/vue.js"></script>
		<!-- <script src="../js/datatable.css"></script> -->
		<link rel="stylesheet" href="../css/boostrap4.css">
		<link rel="stylesheet" href="../../css/styles.css">
					<style>
						body,p,div, span, i, b{
							font-size: 1em;
						}
						.option_btn{
							font-size: 1.3em;
							display: block;
							width: 100%;
						}
						.navbar-brand{
							font-size: 3em;
						}
						nav{
							background-color: #880E4F !important;
							padding: 10px 15px;
							font-family: Impact;
							font-size: 1.5em;
							color:white;
							flex-wrap: wrap;
							display: flex;
							position: relative;
							align-items: center;
						}
						.nav-list{
							padding-top: 0px;
							padding-right: 0px;
						}
						table{
							font-size: 1.3em;
						}
					</style>
		
		<style>
			
		</style>

	</head>
<body>
	
			<!-- end of header -->
							<!-- beginning of header -->
<!-- BEginning of nav -->
				<nav class="" style='border-radius:0px;background-color:#880E4F;border:none;margin:0'>				  					
					  <a  href="#" style="color:#fff; font-size:2em;">
						<?php echo $school_abbr; ?>
					  </a>					
					<form class="mt-2 p-1 ml-2" role="search">
						<div class="form-group">
						<input type="text" class="form-control" placeholder="Search">
						</div>
					
					</form>
					<!-- /.navbar-collapse -->				  
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
		<div class="container-fluid px-0">
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
	<link rel="stylesheet" href="../js/datatable/datatables.min.css">
<script src="../js/datatable/datatable_excel.js"></script>
<script src="../js/datatable/vfs_fonts.js"></script>
<script src="../js/datatable/pdfmake.min.js"></script>
<script src="../js/datatable/datatables.min.js"></script>

				<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../js/admin_script.js"></script>
				<script type="text/javascript" src="../js/admin_script2.js"></script>
				<script type="text/javascript" src="../js/admin_script3.js"></script>
				<script type="text/javascript" src="../js/admin_reg_script.js"></script>
				
				<!-- <script type="text/javascript" src="../js/timing.js"></script> -->
				<script type="text/javascript">
					/*
					 $('.table').DataTable({
						saveState:true,
					});*/
					/*$('.table').dataTable({
						'saveState':true,
					});*/
				</script>
</body>
</html>
