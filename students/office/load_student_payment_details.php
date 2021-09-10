<?php 
	if(isset($_POST['token'])){
		$student_info_id = $_POST['token'];
		include_once('../php/connection.php');
	include_once('../php/student_data.php');
	}else{
		echo '<h3>Access denied...</h3>';
		exit();
	}
		

?>
<div id="">


	
		
		
		
		<div class="col-lg-12">
			
				<h4 style="margin-left:10px;font-size:14pt">
					<button class='btn btn-lg btn-primary' onclick="return false" onmousedown="make_payment_search(<?php echo $class_id;?>)"> 
						<span class='glyphicon glyphicon-arrow-left' style="float:left;margin-right:10px">Back</span>
					</button>
					
					<button class='btn btn-lg btn-success' style="float:right" onclick="return false" onmousedown="load_payment_form(<?php echo $student_info_id;?>)"> 
						<span class='glyphicon glyphicon-ruble' style="float:left;margin-right:10px"><b>Make Payment</b></span>
					</button>
			</h4>
			<div id="class_term_summary"></div>
			<div id="student_payment_summary">
				<?php include_once('student_payment_summary.php')?>
			</div>
		</div>
	
		
		
		
	</div>