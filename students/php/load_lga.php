<?php
	if(isset($_POST['state'])){
	include_once('connection.php');
		$state_id = $_POST['state'];
		$sql_lga = mysqli_query($conn,"SELECT * FROM lga where state_id = '$state_id'");
				if(mysqli_num_rows($sql_lga) > 0){
					$empty = '';
					$select_lga = '-- Select lga --';
					$optt .= $empty.'|'.$select_lga.'||';
					while($lga_array = mysqli_fetch_array($sql_lga)){
							$lga_idd = $lga_array['local_id'];
							$title = $lga_array['title'];
						      $optt .= $lga_idd.'|'.$title.'||';
					}
				}
	}
	mysqli_close($conn);
	echo $optt;
		




 ?>