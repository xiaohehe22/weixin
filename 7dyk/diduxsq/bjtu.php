<?php
header("Content-Type:text/json;charset=utf-8");


 echo "北交";
 echo "\n";

       $mysql = new SaeMysql();


       $sql = "SELECT m.id, m.school, m.contacts, m.grade, m.phone, count(*) 
FROM votemember m
LEFT JOIN voteinfo i ON m.id = i.memberid
WHERE m.school LIKE  '%交%'
GROUP BY m.id
ORDER BY COUNT( * ) DESC";
    
      
        $result = $mysql->getData($sql);

       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
           
            echo "id:".$result[$i]["id"]."   学校:".$result[$i]["school"]."   联系人:".$result[$i]["contacts"]."   班级名称:".$result[$i]["grade"]."   联系方式:".$result[$i]["phone"]."   tickets:".$result[$i]["count(*)"];
            echo "\n";
         }   
        

        $mysql->closeDb();
        

	
?>