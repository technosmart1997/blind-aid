<?php
$data = file_get_contents("php://input");
$details = json_decode($data);

$email = $details->email;
include_once('connect.php');

$cmd ="select * from users where email = '$email' ";

$res= mysqli_query($connect,$cmd);

$count = mysqli_num_rows($res);
if($count==1){
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
}
?>