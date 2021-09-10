<?php
include_once('../php/connection.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];


    $query =  mysqli_query($conn, "select * from school_fees where id='$id'") or die(mysqli_error($conn));
    $row = mysqli_fetch_assoc($query);

    $student_info_id = $row['student_info_id'];
    $session_id = $row['session_id'];
    $class_id = $row['class_id'];
    $amount_paid = $row['amount_paid'];
    $ballance = $row['ballance'];

    $query2 =  mysqli_query($conn, "select * from student_classes where student_info_id = '$student_info_id' and session_id = '$session_id'  and class_id ='$class_id'") or die(mysqli_error($conn));
    $row2 = mysqli_fetch_assoc($query2);
    $school_fees = $row2['school_fees'];


    $DELETE =  mysqli_query($conn, "DELETE FROM school_fees  where id='$id'") or die(mysqli_error($conn));
    $total_paid = 0;
    $query =  mysqli_query($conn, "select * from school_fees where student_info_id = '$student_info_id' and session_id = '$session_id'  and class_id ='$class_id' AND id != $id") or die(mysqli_error($conn));
    while ($row = mysqli_fetch_assoc($query)) {
        $id = $row['id'];
        $student_info_id = $row['student_info_id'];
        $session_id = $row['session_id'];
        $class_id = $row['class_id'];
        $amount_paid = $row['amount_paid'];
       
        
        $ballance = $school_fees - ($total_paid + $amount_paid);
        $total_paid += $amount_paid;
        $status = '1';
        if($ballance <= 0)
        {
            $status = '2';
        }
        $update =  mysqli_query($conn, "UPDATE school_fees SET ballance = '$ballance', status='$status' where id='$id'") or die(mysqli_error($conn));

    }

    echo 'Deleted successfullly...';
} else {
    echo 'Not fouond';
}
