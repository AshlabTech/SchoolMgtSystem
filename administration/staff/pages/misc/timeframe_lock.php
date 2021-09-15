<?php
if($_POST['cdate'] == 0){
	echo 0;
	exit();
}
$date1=date_create(date('Y-m-d'));
$date2=date_create($_POST['cdate']);
$diff=date_diff($date1,$date2);
$dd =  $diff->format("%R%a");	
if($dd < 1){
	//echo 0;
}else{
	echo $dd;// $diff->format("%a");	

}
	


?>