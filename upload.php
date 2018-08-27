<?php

require_once('connect.php');

if(!empty($_FILES))
{
    $name = $_FILES['file']['tmp_name'];
    
    $path = 'uploads/'. $_FILES['file']['name'];
    move_uploaded_file($name,$path);

}
?>