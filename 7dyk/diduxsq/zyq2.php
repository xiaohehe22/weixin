<?php
header("Content-Type:text/json;charset=utf-8");



       $mysql = new SaeMysql();

 $sql = "SELECT count(*) FROM voteinfo2";

$result = $mysql->getData($sql);

echo "投票总数".$result[0]["count(*)"];
 echo "\n";


       $sql = "SELECT m.id,m.membername,m.type,m.phone,count(*) FROM votemember2 m  LEFT JOIN voteinfo2 i ON m.id = i.memberid GROUP BY m.id order by count(*) desc";
    
      
        $result = $mysql->getData($sql);

       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
           
            echo "id:".$result[$i]["id"]."   name:".$result[$i]["membername"]."   type:".$result[$i]["type"]."   phone:".$result[$i]["phone"]."   tickets:".$result[$i]["count(*)"];
            echo "\n";
         }   
        

        $mysql->closeDb();
        

	
?>