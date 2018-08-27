<?php

include_once('connect.php');

$data = file_get_contents("php://input");
$details = json_decode($data);

$user_email = $details->email;
$h1 = $details->hb1;
$h2 = $details->hb2;
$h3 = $details->hb3;


$q  = "select * from users where email = '$user_email'";
$response= mysqli_query($connect,$q);
$row = mysqli_fetch_assoc($response);

$hcity= $row['hCity'];
$hstate= $row['hstate'];
$hcountry= $row['hcountry'];
$pcountry= $row['pcountry'];
$pcity= $row['pcity'];
$id = $row['user_id'];

$cmd= "select * from hobby where hobby1 = '$h1' OR hobby1 = '$h2' OR hobby1 = '$h3' and hobby2 = '$h1' OR hobby2 = '$h2' OR hobby2 = '$h3' and hobby3 = '$h1' OR hobby3 = '$h2' OR hobby3 = '$h3'  and  hobby5 = '$h1' OR hobby5 = '$h2' OR hobby5 = '$h3' and hobby4 = '$h1' OR hobby4 = '$h2' OR hobby4 = '$h3'";

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
       $cuid = $row['user_id'];
       if($cuid != $id)
       {
            $q2 = "select * from users inner join hobby  on users.user_id = hobby.user_id and users.user_id = '$cuid' and users.pcity = '$pcity'";
           $res2 = mysqli_query($connect,$q2);
           $r2 = mysqli_fetch_assoc($res2);
           if($r2!=null)
           {
           $output[] = $r2;
           }
       }
   }
}


$final['res'] = $error;
$final['output'] = $output;
echo json_encode($final);


    
?>