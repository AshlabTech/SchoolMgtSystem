<?php 

session_start();

 
/* $sll = $conn->query("SELECT student_no AS nom FROM `student_login_info` ");
 while($row = $sll->fetch_assoc()){
     $key = $row['nom'];
     $password = base64_encode(md5($school_abbr).md5($row['nom']));
    $fire = $conn->query("UPDATE `student_login_info` AS s SET  `password`= '$password' WHERE student_no = '$key'" )or die(mysqli_error($conn));    
     if($fire){
         echo 'success';
     }
 }
 */
	if(isset($_REQUEST['student_pass_input'])){
		include_once('php/connection.php');
		$student_no_input = $_REQUEST['student_no_input'];
		$student_pass_input = $_REQUEST['student_pass_input'];
		$error_message = null;
		if(empty($student_pass_input) || empty($student_no_input)){
			$error_message = 'Student No. and password is required';
		}else{
			 $password = base64_encode(md5($school_abbr).md5($student_pass_input));
			 
			$student_check = mysqli_query($conn,"SELECT * FROM student_login_info WHERE student_no = '$student_no_input' AND password = '$password' and status ='1'") or die(mysqli_error($conn));
			$student_check_rows = mysqli_num_rows($student_check);
				if($student_check_rows > 0){
					$row = mysqli_fetch_array($student_check);
					$student_id = $row['student_id'];
					$_SESSION['PSS_STUDENT_LOGIN_ID'] = $student_id;
					$_SESSION["result_code_attempt"] = 0;
					header('location:public/');
				}else{
					$error_message = 'Incorrect student no or password';
				}
			
			
		}
	}
	if(isset($_SESSION['PSS_STUDENT_LOGIN_ID'])){
		header('location:public/');		
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V1</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="Login_v1/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="Login_v1/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="Login_v1/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="Login_v1/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="Login_v1/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="Login_v1/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="Login_v1/css/util.css">
	<link rel="stylesheet" type="text/css" href="Login_v1/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="Login_v1/images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" action="index.php" method="POST">
					<span class="login100-form-title">
						Student Login
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Student No. is reqluired">
						<input class="input100" type="text" name="student_no_input" id="student_no_input" value="<?= @$_REQUEST['student_no_input'];?>"  placeholder="Student No.">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="student_pass_input"  id="student_pass_input" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button class="login100-form-btn" id="login_btn">
							Login
						</button>
					</div>
					<div id="login_status"> 
					<hr/>
						<?php 
						
							if(@$error_message != null){
								echo '<div class="alert alert-danger">'.@$error_message.'</div>';
							}
						?>
					</div>
					<div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="Login_v1/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="Login_v1/vendor/bootstrap/js/popper.js"></script>
	<script src="Login_v1/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="Login_v1/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="Login_v1/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="Login_v1/js/main.js"></script>
	
	<script type="text/javascript" src="js/admin_script.js"></script>
				
				<script type="text/javascript" src="js/admin_reg_script.js"></script>

</body>
</html>