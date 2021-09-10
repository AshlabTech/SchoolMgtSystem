<?php
include_once('connection.php');

if (isset($_POST['token'])) {
    $staff_info_id = $_POST['token'];
    $password = $_POST['password'];

    $md5_password = md5($password);

    $sql_update = "UPDATE staff_login_info SET password ='$md5_password' where staff_info_id = '$staff_info_id'";
    $query_update = mysqli_query($conn, $sql_update) or die(mysqli_error($conn));
    if ($query_update) {

        echo  '<div class="alert alert-success">Password has been changed successfully..</div>';
    } else {
        echo  '<div class="alert alert-danger">Oops! somethng went wrong!..</div>';
    }
}
