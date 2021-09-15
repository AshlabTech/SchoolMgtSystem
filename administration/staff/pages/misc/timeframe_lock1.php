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

	$date1=date_create(date('Y-m-d'));
$date2=date_create($_POST['cdate']);
$diff=date_diff($date1,$date2);
	$dd =  $diff->format("%R%a");
	echo $dd. 45;
	if($dd < 1){
		//echo 0;
	}else{
		echo $diff;
	}

	
?>