<?php

$score = $_GET["score"];
getrank($score);

function getrank($score){

     $mysql = new SaeMysql();
     $sql = 'select * from turkey order by score desc '; 
     $result = $mysql->getData($sql);
    
    $num =count($result);
    $rank=count($result)+2;
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            if($score>$result[$i]["score"]){
            $rank=$i+1;     
                break;
            }
         }   
        
        
        $mysql->closeDb();
        

    echo '{"rank":"'. $rank.'","num":"'. $num.'"}';
       
}

?>
