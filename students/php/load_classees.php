<?php 
include_once('connection.php');
	 $section = mysqli_real_escape_string($conn,$_POST['token']);
			if($section == 1)
			$query = mysqli_query($conn,"select * from classes where school_section_id <=2") or die(mysqli_error($conn));
			else
			$query = mysqli_query($conn,"select * from classes where school_section_id >=3") or die(mysqli_error($conn));
		
		echo '<option value="" selected></option>';
			while($class_array = mysqli_fetch_array($query)){
				$class_id = $class_array['class_id'];
				$class = $class_array['class_name'];
				echo '<option value="'.$class_id.'">'.$class.'</option>';
		}
?>