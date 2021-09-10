<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
include("php/dbconnect.php");

if ($_GET['type']='remveM') {
	echo $sel = rtrim($_GET['selected'],',');
	$selected = explode(',', $sel);
	
	foreach ($selected as $key) {
		$sql = "DELETE FROM merge_all WHERE id = '$key'";
		$q = $conn->query($sql);
	}
 	echo 1;
}



?>