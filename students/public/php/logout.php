<?php
session_start();
	session_destroy();
//	echo $_SESSION['PSS_STUDENT_LOGIN_ID']);
header('location:../');				
?>