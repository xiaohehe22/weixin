<?php
include 'vote.php';

header("Content-Type:text/json;charset=utf-8");

$group = $_GET["membername"];
$school = $_GET["school"];
$contacts= $_GET["name"];
$phone = $_GET["phone"];

$major="test";
$membername="test";


 $action = new voteAction();
 $content=$action->memberSigh($school,$group,$major,$membername,$contacts,$phone);
 
    
 echo '{"result":'.$content.'}'; 


?>