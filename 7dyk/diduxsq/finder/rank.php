<?php

$score = $_GET["score"];
$level = $_GET["level"];
getrank($score,$level);

function getrank($score,$level){

     $mysql = new SaeMysql();
     $sql = 'select * from finder order by level desc,score asc '; 
     $result = $mysql->getData($sql);
    
    $num =count($result);
    $rank=count($result)+1;
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
           if($level>$result[$i]["level"]){
                $rank=$i+1;     
                break;
            }
            else if($level==$result[$i]["level"]&&$score<$result[$i]["score"]){
                $rank=$i+1;     
                break;
            }
         }   
        
        
        $mysql->closeDb();
        

    echo '{"rank":"'.$rank.'","num":"'.$num.'"}';
       
}

?>
