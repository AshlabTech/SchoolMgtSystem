<?php session_start(); ?>
<?php
if (isset($_SESSION['staff_info_id'])) {
} else {
    header('location:../');
}
include_once('../php/connection.php');


$session_id = $_GET['session_id'];
$year  = date('Y');
$sql =  "SELECT m.*,m.id as pid,pd.*,pd.id as pdid, pd.amount as expected_amount,c.class_name,i.adm_no, CONCAT(i.first_name,' ', i.other_name,' ', i.last_name) as fullname FROM  misc_payments AS m 
                INNER JOIN misc_payment_details AS pd ON pd.id = m.misc_payment_detail_id
                INNER JOIN student_info as i ON i.student_info_id = m.student_info_id
                INNER JOIN classes as c ON c.class_id=m.class_id WHERE m.year='$year' ";
$run1 =  mysqli_query($conn, $sql) or die(mysqli_error($conn));
$payment_history = [];
if ($run1->num_rows > 0) {
    while ($row = $run1->fetch_assoc()) {
        $payment_history[] = $row;
    }
}

$sql =  "SELECT * FROM  misc_payment_history WHERE SUBSTRING(payment_date,1,4)='$year' ";
$run1 =  mysqli_query($conn, $sql) or die(mysqli_error($conn));
$payment_historyx = [];
if ($run1->num_rows > 0) {
    while ($row = $run1->fetch_assoc()) {
        $payment_historyx[] = $row;
    }
}
$data = array();
$data[]= $payment_history;
$data[]= $payment_historyx;

echo json_encode($data);

?>