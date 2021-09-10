<?php
function checkDate1($d){
		$expire = strtotime($d);
	$today = strtotime("today midnight");
	$today = strtotime(date("Y-d-m"));
		
		if($today >= $expire){
			return 0;
		} else {
			return 1;
		}
	}
	if($_POST['cdate'] !=0){
		echo checkDate1($_POST['cdate']);
	}else{
		echo 0;
	}


?>