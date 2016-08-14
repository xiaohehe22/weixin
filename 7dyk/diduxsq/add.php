<?php

header("Content-Type:text/json;charset=utf-8");


$id = $_GET["id"]? $_GET["id"]:1;


       $mysql = new SaeMysql();

 
        $sql = "insert into voteinfo(memberid,weixinid)values('".$id."','test')";
        $mysql->runSql($sql);  

 $sql = "SELECT count(*) FROM voteinfo";

$result = $mysql->getData($sql);

echo "投票总数".$result[0]["count(*)"];
 echo "\n";


       $sql = "SELECT m.id,m.grade,count(*) FROM votemember m  LEFT JOIN voteinfo i ON m.id = i.memberid GROUP BY m.id order by count(*) desc";
    
      
        $result = $mysql->getData($sql);

       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
           
            echo "id:".$result[$i]["id"]."   name:".$result[$i]["grade"]."   tickets:".$result[$i]["count(*)"];
            echo "\n";
         }   
        

        $mysql->closeDb();

?>