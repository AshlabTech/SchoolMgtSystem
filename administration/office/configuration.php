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

$error = '';
$exists = 0;
$orgName ="";
$addressName="";
$sql = "SELECT * FROM school LIMIT 1";
$q = $conn->query($sql);

if($q->num_rows>0)
{
	$row  = $q->fetch_assoc();
	 $orgName = $row['name'];
	 $addressName = $row['address'];
	 $addressName = $row['address'];
	 $abbr = $row['abbr'];
	 $file = $row['image'];
	//echo '<script type="text/javascript">window.location="setting.php?act=1"; </script>';
	$exists =1;
}else
{
$error = '<div class="alert alert-danger">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <strong>Error!</strong> No Account Has Been created for this system
</div>';
}



if (isset($_POST['save'])) {
	$name1 = mysqli_real_escape_string($conn,$_POST['nameOfOrg']);
	$address1 = mysqli_real_escape_string($conn,$_POST['address']);
	$abbr = mysqli_real_escape_string($conn,$_POST['abbr']);	
$target_dir = "../../images/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$filename = basename($_FILES["file"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
if ($imageFileType != 'jpg') {
	die('invalid image upload');
}


	if($exists==1){
		$sql = $conn->query("UPDATE school SET name='$name1', address='$address1', abbr= '$abbr', image = '$filename' ") or die(mysqli_error($conn));

		move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
		if($sql){
		echo '<script type="text/javascript">alert("saved Succeffully"); </script>';
		}else{
			echo '<script type="text/javascript">alert("error update"); </script>';			
		}


	}else{
		$sql = "INSERT INTO organization (name,address,abbr,image) VALUES('$name1','$address1', '$abbr', '$filename')";
			
			move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);

			if($sql){
				echo '<script type="text/javascript">alert("created good"); </script>';
			}else{
				echo '<script type="text/javascript">alert("error insert"); </script>';			
			}
	}
}

?>
	<head>		
			
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
<div class="row" style="padding: 30px;">
	<ul class="panel">
		<li class="btn btn-primary topbtn" rel="school">Manage School</li>
		<li class="btn btn-primary topbtn" rel="head">School Head</li>
	</ul>
	<div id="school" class="row pageBody" style="display: none;">		
		 <div class="panel panel-primary col-sm-6 col-md-6 col-lg-6">
                        <div class="panel-heading">
                          Organization Config
                        </div>
						<form action="" method="post" id="signupForm1" class="form-horizontal"  enctype="multipart/form-data">
                        <div class="panel-body">						
						<div class="form-group">
								<label class="col-sm-4 control-label" for="nameOfOrg1">Name of Organization</label>
								<div class="col-sm-5">
									<input type="text" value="<?php echo $orgName; ?>" class="form-control" id="nameOfOrg1" name="nameOfOrg"  />
								</div>
						</div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="abbr">Abbr</label>
								<div class="col-sm-5">
									<input type="text" value="<?php echo $abbr; ?>" class="form-control" id="abbr" name="abbr"  />
								</div>
						</div>
						<div class="form-group">
								<label class="col-sm-4 control-label" for="Old">Address</label>
								<div class="col-sm-5">
									<input type="text" value="<?php echo $addressName; ?>" class="form-control" id="address1" name="address"  />
								</div>
						</div>
							
							
							<div class="form-group">
								<label class="col-sm-4 control-label">Logo</label>
								<div class="col-sm-5">
									 <input class="form-control" name="file" accept="jpg" id="file" type="file" required="">
								</div>
							</div>						
						<div class="form-group">
								<div class="col-sm-9 col-sm-offset-4">
									<button type="submit" name="save" class="btn btn-primary">Save </button>
								</div>
							</div>
                         
                           
                           
                         
                           
                         </div>
							</form>
							
                        </div>

		 <div class="panel panel-primary col-sm-6 col-md-6 col-lg-6">
		 	<img src="../../images/<?php echo $file;?>">
		 </div>
	</div>

	<div id="head" class="pageBody" style="display: none;">
		<iframe src="school_head.php" width="100%" height="600px;" style="border: none;"></iframe>
	
	</div>
</div>

<script >
	$('#school').show();
	$('.topbtn').click(function(){
		var btnId = '#' + $(this).attr('rel');
		$('.pageBody').hide();
		$(btnId).show();

	})
</script>