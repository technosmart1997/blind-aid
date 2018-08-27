<?php


$data = file_get_contents("php://input");
$details = json_decode($data);

include_once('connect.php');

$username = $details->username;


$cmd = "select * from user where username = '$username'";
$res = mysqli_query($connect,$cmd);
$count = mysqli_num_rows($res);

$response = array();

if($count == 1)
{
    $response['status'] = "valid";
    $response['message']= "Username Exists";
}
else
{
    $response['status'] = "invalid";
    $response['message'] ="Username Does Not Exist!!";
}
    echo json_encode($response);

?>

