<?php
header("Content-Type:text/json;charset=utf-8");

$tourl="http://2.diduxsq.sinaapp.com/finder/index.html"; 
$fromurl="2.diduxsq.sinaapp.com/finder/index.html"; 

if(strpos($_SERVER['HTTP_REFERER'],$fromurl)==-1)
{
header("Location:".$tourl); 
    exit;
}


if($_SERVER["REQUEST_METHOD"]=="GET"){

getdata();
    
}else if($_SERVER["REQUEST_METHOD"]=="POST"){
    
 $name = $_POST["name"];
 $phone = $_POST["phone"];
 $score = $_POST["score"];
 $level = $_POST["level"];
    
  data($name,$phone,$score,$level);

}



function data($name,$phone,$score,$level){

    $mysql = new SaeMysql();
    $sql = "insert into finder(name,score,phone,level) values ('$name','$score','$phone','$level')";
    $mysql->runSql($sql);  

    sleep(1);

     $sql = 'select * from finder order by level desc,score asc limit 20'; 
     $result = $mysql->getData($sql);

    $jsonre =  '[{"result":"'.count($result).'"}';
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            $jsonre=$jsonre.',{"name":"'.$result[$i]["name"].'","phone":"'.substr($result[$i]["phone"],0,3)."****".substr($result[$i]["phone"],-4).'","score":"'.$result[$i]["score"].'","level":"'.$result[$i]["level"].'"}';
         }   
        
        
        $jsonre = $jsonre.']';
        $mysql->closeDb();
        

        echo $jsonre;
       
}


function getdata(){

    $mysql = new SaeMysql();

     $sql = 'select * from finder order by level desc,score asc limit 20'; 
     $result = $mysql->getData($sql);

    $jsonre =  '[{"result":"'.count($result).'"}';
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            $jsonre=$jsonre.',{"name":"'.$result[$i]["name"].'","phone":"'.substr($result[$i]["phone"],0,3)."****".substr($result[$i]["phone"],-4).'","score":"'.$result[$i]["score"].'","level":"'.$result[$i]["level"].'"}';
         }   
        
        
        $jsonre = $jsonre.']';
        $mysql->closeDb();
        

        echo $jsonre;
       
}



?>