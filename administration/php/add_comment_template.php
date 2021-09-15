<?php
		//  make mysqli database connection
	  include_once('connection.php');	


		//gather all ajax posted data     
	$date = date('Y-m-d');
    $comment = $_POST['comment'];
    $comment_type = $_POST['comment_type'];
    $id = $_POST['id'];
if($_POST['type']=='add'){
    $sql = "INSERT INTO `comment_template`(`id`, `comment`, `comment_type`, `status`) VALUES (null,'$comment','$comment_type','1')";
        $run = $conn->query($sql) or die(mysqli_error($conn));
        $last_id = $conn->insert_id;        
   echo json_encode(['id'=>$last_id, 'success'=>200]);
}else{    
    $sql = "UPDATE `comment_template` SET `comment`='$comment',`comment_type`='$comment_type'
             WHERE id ='$id'";
    $run = $conn->query($sql) or die(mysqli_error($conn));
    echo 200;

}
?>