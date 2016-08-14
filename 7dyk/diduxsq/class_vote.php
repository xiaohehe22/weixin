<?php
include 'vote.php';

header("Content-Type:text/json;charset=utf-8");

 $school = $_GET["school"];
 $grade = $_GET["grade"];
 $major = $_GET["major"];
 $membername = $_GET["membername"]; 
 $contacts = $_GET["contacts"];
 $phone = $_GET["phone"];

 $action = new voteAction();
 $content=$action->memberSigh($school,$grade,$major,$membername,$contacts,$phone);
 
    
  echo '{"result":'.$content.'}'; 


?>