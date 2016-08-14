<?php
header("Content-Type:text/json;charset=utf-8");


if($_SERVER["REQUEST_METHOD"]=="GET"){
 $start = $_GET["start"];
 $end = $_GET["end"];
 getdata($start,$end);
    
}else if($_SERVER["REQUEST_METHOD"]=="POST"){

   echo "error";
}


//获取点击记录
function getdata($start,$end){


    $mysql = new SaeMysql();
        $sql = 'select i.nameinfo,count(*) from visitinfo v,idinfo i where i.name=v.name and v.time>"'.$start.' 17:00:00" and v.time<"'.$end.' 17:00:00" group by i.name';
        $result = $mysql->getData($sql);

        $jsonre =  '[';
        $sum=0;
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            $jsonre=$jsonre.'{"name":"'.$result[$i]["nameinfo"].'","num":"'.$result[$i]["count(*)"].'"},';
            $sum=$sum+$result[$i]["count(*)"];
         }   
        
        
        $jsonre = $jsonre .'{"name":"sumvisit","num":"'.$sum.'"}]';
        $mysql->closeDb();
        

        echo $jsonre;
       
}


?>