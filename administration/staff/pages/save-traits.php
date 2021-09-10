<?php
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
include '../php/connection.php';
$stid = $_POST['stid'];
$selVal = explode(',', rtrim($_POST['selVal'],','));
$sid = $_POST['sid'];
$tid = $_POST['tid'];
$bid = $_POST['bid'];
$result =0;
if ($_POST['type']=='affective') {
	for ($i=0; $i < sizeof($selVal) ; $i++) { 
		$aids = explode(';',$selVal[$i]);
		$aid = $aids[0];
		@$rate = $aids[1];
		$chk_a = $conn->query("SELECT * FROM student_a_traits WHERE student_id='$stid' AND affective_domain_id='$aid' AND term_id='$tid' AND session_id='$sid'");
		if ($chk_a->num_rows>0) {
			$update = $conn->query("UPDATE student_a_traits SET rate='$rate' WHERE student_id='$stid' AND affective_domain_id='$aid' AND term_id='$tid' AND session_id='$sid'");
			if ($update) {
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$run =$conn->query("INSERT INTO `student_a_traits`(`id`, `student_id`, `affective_domain_id`, `rate`, `term_id`, `session_id`) VALUES (null,'$stid', '$aid', '$rate','$tid','$sid')");
		}
	}
	echo $result;
}

if ($_POST['type']=='psycomotor') {
	for ($i=0; $i < sizeof($selVal) ; $i++) { 
		$pids = explode(';',$selVal[$i]);
		$pid = $pids[0];
		@$rate = $pids[1];
		$chk_a = $conn->query("SELECT * FROM `student_p_traits` WHERE student_id='$stid' AND psycomotor_id='$pid' AND term_id='$tid' AND session_id='$sid'");
		if ($chk_a->num_rows>0) {
			$update = $conn->query("UPDATE `student_p_traits`SET rate='$rate' WHERE student_id='$stid' AND psycomotor_id='$pid' AND term_id='$tid' AND session_id='$sid'");
			if ($update) {
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$run =$conn->query("INSERT INTO `student_p_traits`(`id`, `student_id`, `psycomotor_id`, `rate`, `term_id`, `session_id`)  VALUES (null,'$stid', '$pid', '$rate','$tid','$sid')");
			if($run){
				$result = 1;
			}else{
				echo mysqli_error($conn);
			}
		}
	}
	echo $result;
}
?>