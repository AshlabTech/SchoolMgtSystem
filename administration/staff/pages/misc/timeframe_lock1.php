<?php
function checkDate1($d){
		$expire = strtotime($d);
		$today = strtotime("today midnight");
		if($today >= $expire){
			return 0;
		} else {
			return 1;
		}
	}
	if($_POST['cdate'] !=''){
		echo checkDate1($_POST['cdate']);
	
	}else{
		echo 0;
	}

	
?>