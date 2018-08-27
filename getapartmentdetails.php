<?php

include_once('connect.php');

$data = file_get_contents("php://input");
$details = json_decode($data);

$apart = $details->apartment;
$block = $details->blk;


$q  = "select * from apartment where apartmentname = '$apart'";
$response= mysqli_query($connect,$q);
$row = mysqli_fetch_assoc($response);

$aid = $row['apartment_id'];

$cmd= "select * from apartment_details where apartment_id = '$aid' and block = '$block'";

$final = array();

$output = array();
$error = array();
$res= mysqli_query($connect,$cmd);

$count = mysqli_num_rows($res);
if($count>0)
{
    $error['res'] = 'Done';
   while($row=mysqli_fetch_assoc($res)) 
   {
       {
            $output[] = $row;
       }
}
}


$final['res'] = $error;
$final['output'] = $output;
echo json_encode($final);


    
?>