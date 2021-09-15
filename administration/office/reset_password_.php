<?php
include_once('../php/connection.php');
$data  = $_POST['data'];

$password = md5($school_abbr.'1234');
 if($data != 'reset all'){
     $r = $conn->query("SELECT * FROM `student_login_info` WHERE REPLACE(student_no, '/', '')=UPPER('$data')");     
     if($r->num_rows > 0){         
         $run = $conn->query("UPDATE student_login_info SET password = '$password' WHERE REPLACE(student_no, '/', '')=UPPER('$data')");
         echo 200;
     }else{
         echo 419;
     }
 }else if($data == 'reset all'){
    $run = $conn->query("UPDATE student_login_info SET password = '$password' ");
    echo 200;
 }else{
     echo 419;
 }


?>