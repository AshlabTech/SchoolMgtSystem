<?php
		//  make mysqli database connection
	  include_once('connection.php');	


		//gather all ajax posted data     
	$date = date('Y-m-d');
    $category = $_POST['category'];
    $name = $_POST['name'];
    $abbr = $_POST['abbr'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $id = $_POST['id'];
if($_POST['type']=='add'){
    $sql = "INSERT INTO `misc_payment_details`(`id`, `name`, `abbr`, `amount`, `session`, `category`) VALUES 
            (null, '$name', '$abbr','$amount', 0, '$category')";
        $run = $conn->query($sql) or die(mysqli_error($conn));
        $last_id = $conn->insert_id;        
   echo json_encode(['id'=>$last_id, 'success'=>200]);
}else{    
    $sql = "UPDATE `misc_payment_details` SET `name`='$name',`abbr`='$abbr',
                `amount`='$amount',`category`='$category' WHERE id= $id";
    $run = $conn->query($sql) or die(mysqli_error($conn));
    echo 200;

}
?>