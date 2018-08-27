<?php

$data = file_get_contents("php://input");
$details = json_decode($data);

session_start();
include_once('connect.php');

$email = $details->emailid;
$password =$details->password;

$cmd = "select * from users where email = '$email' and password = '$password'";
$res = mysqli_query($connect,$cmd);
$count = mysqli_num_rows($res);

$response = array();

if($count == 1)
{
    $row = mysqli_fetch_assoc($res);
    session_regenerate_id();
    $response['status'] = "loggedin";
    $response['session_id']=session_id();
    $response['email']=$email;
    $response['user_id']=$row['user_id'];
    $response['fname']=$row['first_name'];
    $response['email']=$email;
    
    $_SESSION['loggedin']=true;
    $_SESSION['id']=$response['session_id'];
    
}
else
{
    $response['status'] = "loggedout";      
}

echo json_encode($response);
?>