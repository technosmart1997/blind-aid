                                                       +<?php
$data = file_get_contents("php://input");
$details = json_decode($data);

include_once('connect.php');

$fname = $details->fname;
$lname =$details->lname;
$gen = $details->gen;
$rpass = $details->rpass;
$remail = $details->remail;
$homecity =$details->homecity;
$presentcity = $details->presentcity;
$presentcountry = $details->presentcountry;
$homecountry = $details->homecountry;
$contact = $details->con;

$response = [];

$cmd = "select * from users where email = '$remail'";
$res = mysqli_query($connect,$cmd);
$count = mysqli_num_rows($res);

if($count == 1)
{
    $response['status']="Sorry, Email Id Already Exists";
}else
{

    $cmd = "insert into users (first_name,lastname,email,password,sex,hCity,hcountry,pcountry,pcity,contactno) values ('$fname','$lname','$remail','$rpass','$gen','$homecity','$homecountry','$presentcountry','$presentcity','$contact')";
    $res = mysqli_query($connect,$cmd);
    if($res)
    {
    $response['status'] = "OK";
    }
}
echo json_encode($response);
?>