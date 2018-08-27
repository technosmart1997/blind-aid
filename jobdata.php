<?php

include_once('connect.php');

$data = file_get_contents("php://input");
$details = json_decode($data);

$company_name = $details->company;
$dept = $details->department;
$email = $details->email;

$q1 = "select * from users where email = '$email'";
$res1 = mysqli_query($connect,$q1);
$r1 = mysqli_fetch_assoc($res1);
$id = $r1['user_id'];

$final = array();

$res = array();
$job = array();
$info = array();

$q = "select * from job where companyname = '$company_name' and departmentname='$dept'";
$response= mysqli_query($connect,$q);

$count = mysqli_num_rows($response);

if($count>0){
    $res['error'] = 'Done';
   while($row = mysqli_fetch_assoc($response)){
       $cuid = $row['user_id'];
       if($cuid != $id)
       {
           $q2 = "select * from users inner join job on users.user_id = job.user_id and users.user_id = '$cuid'";
           $res2 = mysqli_query($connect,$q2);
           $r2 = mysqli_fetch_assoc($res2);
           $info[] = $r2;
       }
       
   } 
}

$final['res'] = $res;
$final['info'] = $info;

echo json_encode($final);    
?>