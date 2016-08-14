<?php
header("Content-Type:text/json;charset=utf-8");

$fromurl="http://2.diduxsq.sinaapp.com/ThanksgivingDay/index.html"; //跳转往这个地址。
if( $_SERVER['HTTP_REFERER'] == "" )
{
header("Location:".$fromurl); 
    exit;
}
 $name = $_GET["name"];
 $phone = $_GET["phone"];
 $score = $_GET["score"];
if(!isset($_GET["score"])||empty($_GET["score"])||$score>160){
header("Location:".$fromurl); 
    exit;
}else{
    data($name,$phone,$score);
}



function senddata($name,$phone,$score){


    $mysql = new SaeMysql();
    $sql = "insert into turkey(name,score,phone) values ('$name','$score','$phone')";
    $mysql->runSql($sql);  
    $mysql->closeDb();
        

    echo '{"result":"success"}';
       
}


function getdata(){


     $mysql = new SaeMysql();
     $sql = 'select * from turkey order by score desc '; 
     $result = $mysql->getData($sql);

    $jsonre =  '[{"result":"'.count($result).'"}';
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            $jsonre=$jsonre.',{"name":"'.$result[$i]["name"].'","score":"'.$result[$i]["score"].'"}';
         }   
        
        
        $jsonre = $jsonre.']';
        $mysql->closeDb();
        

        echo $jsonre;
       
}




function data($name,$phone,$score){

    $mysql = new SaeMysql();
    $sql = "insert into turkey(name,score,phone) values ('$name','$score','$phone')";
    $mysql->runSql($sql);  

    sleep(1);

     $sql = 'select * from turkey order by score desc limit 20'; 
     $result = $mysql->getData($sql);

    $jsonre =  '[{"result":"'.count($result).'"}';
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            $jsonre=$jsonre.',{"name":"'.$result[$i]["name"].'","phone":"'.substr($result[$i]["phone"],0,3)."****".substr($result[$i]["phone"],-4).'","score":"'.$result[$i]["score"].'"}';
         }   
        
        
        $jsonre = $jsonre.']';
        $mysql->closeDb();
        

        echo $jsonre;
       
}



?>