	<?php session_start(); ?>
<?php
	if(isset($_SESSION['staff_info_id'])){
		
	}
	else{
		header('location:../');
	}
	include_once('../php/connection.php');
	
		//Export attendance to excel 
		if(isset($_GET['cl']) and isset($_GET['excel_m'])){
			$_SESSION['cl'] = $_GET['cl'];
			$_SESSION['excel_m'] = $_GET['excel_m'];
				//include_once('export_attendance_toExcel.php');
					header('location:php/print_attendance.php');
		}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Peace Group of Schs. | MOKWA </title>
			
		<link rel="shortcut icon" href="../../images/e_portal.png">
	   <meta name="viewport" content="width=device-width, initial-scale=1" >
		<link rel="stylesheet" href="../../css/bootstrap.css">
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.css">
		<link rel="stylesheet" href="../../css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="../../css/styles.css">
		
				
				
				<!-- inlcude all javascript files -->
				<script type="text/javascript" src="../../js/jquery-1.10.2.js"></script>
				<script type="text/javascript" src="../js/admin_script.js"></script>
				<script type="text/javascript" src="../js/admin_reg_script.js"></script>
				<script type="text/javascript" src="../js/timing.js"></script>

		
		<style>
			
		</style>

	</head>
<body>

				<div class="row" style="margin-bottom:10px">
						
					<div class="col-md-12 " style="padding:10px 20px 10px 0px">
					<div style="font-size:20px;font-family : 'Arial Black';padding-left:20px;width:200px;float:left"><i class="menu-icon fa fa-desktop"></i> <?php echo $school_abbr; ?> </div>
					
						<div class="form-inline " style="margin-left:220px;text-align:right;">
								  <div class="form-group">
								
									<select class="form-control" id="select_class" onchange="load_attendance_sheet(this.value)">
										<?php 
											$query = mysqli_query($conn,"select * from classes") or die(mysqli_error($conn));
											while($class_array = mysqli_fetch_array($query)){
												$class_id = $class_array['class_id'];
												$class = $class_array['class_name'];
												$hash_class = md5($class_id);
												
												if($_GET['token'] == $hash_class)
													echo '<option value="'.$hash_class.'" selected>'.$class.'</option>';
												else
													echo '<option value="'.$hash_class.'" >'.$class.'</option>';
											}
									?>
									</select>
								  </div>
								  <div class="form-group">
								
									<select class="form-control" id="current_session">
										
										<?php 
											$query2 = mysqli_query($conn,"select * from session where status = '1'") or die(mysqli_error($conn));
											while($class_array2 = mysqli_fetch_array($query2)){
												$session_id = $class_array2['section_id'];
												$session = $class_array2['section'];
												$first_yr = substr($session,0,4);
												$second_yr = substr($session,-4);
												echo '<option value="'.$session_id.'">'.$session.'</option>';
												
												$_SESSION['session_id'] = $session_id;
										
											}
									?>
									</select>
								  </div>
								  <div class="form-group">
								
									<select class="form-control" id="select_yr">
									<?php 
											$year = mysqli_query($conn,"select * from year where status = '1'") or die(mysqli_error($conn));
											while($year_array = mysqli_fetch_array($year)){
												$current_year = $year_array['year'];
												if($current_year == $first_yr)
													echo '<option value="'.$current_year.'" selected>'.$current_year.'</option>';
												else
													echo '<option value="'.$second_yr.'" >'.$second_yr.'</option>';
													
												$_SESSION['current_year']= $current_year;
										
											}
												//Export attendance to excel 
												//include_once('export_attendance_toExcel.php');
									?>
										
					
									</select>
								  </div>
								  
								
							</div>
					</div>
				</div>
						<div style="width:100%;margin:0;padding:10px;background-color:#337ab7; border-color: #2e6da4;color:#fff;border-radius:0">
							<div  class="" id="" >
								
								<i class="ace-icon fa fa-home home-icon" style=""></i><a href="index.php"  style="color:#fff;">   <b>Go Back</b></a>
								<span style="float:right;font-family:;"><span id="timing"></span><strong><?php echo @date('M-D-Y');?></strong></span>
							</div>
						</div>
				<div class="row">
					<div class="col-md-12" id="attendance_sheet_wrap">
						<?php include_once('attendance_sheet.php'); ?>
					</div>
				</div>
					<script>
  $(function () {
    $('#myTab li').on('click',function(){
		var name = $(this).attr("name");
	
		  $('#myTab li').removeAttr('class') ;
		  $(this).addClass('active');
		  
		  $(".tab-content div").removeAttr('class');
		  $(".tab-content div").addClass('tab-pane');
		   
		   $("#"+name).removeAttr('class');
		  $("#"+name).addClass('tab-pane active');
	
	});
  })
</script>

	</body>
</html>