<?php 

	$month_full = @date('M');
	$day_full = @date('D');
	$day = @date('d');
	$year_full = @date('Y');
	$date = @date('d-m-Y  h:i:s');
?>
<div id="date_time_wrap">
	<div id="date_time"><?php echo $date; ?></div>
	<div id="month">
	<?php 
		echo $day;
		echo '<p style="margin-top:-30px;font-size:24pt" >'.$month_full.'</p>';

	?></div>
	<div id="day_full"><?php echo $day_full.' , '.$year_full; ?></div>

</div>