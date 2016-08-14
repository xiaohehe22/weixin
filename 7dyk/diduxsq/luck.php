<?php
header("Content-Type:text/json;charset=utf-8");


if($_SERVER["REQUEST_METHOD"]=="GET"){
 $data1 = $_GET["data1"];
 $data2 = $_GET["data2"];
 getdata($data1,$data2);
    
}else if($_SERVER["REQUEST_METHOD"]=="POST"){

   echo "error";
}


//获取点击记录
function getdata($data1,$data2){


    $mysql = new SaeMysql();
    $sql = 'select p.number,p.password from lucklist l,luckpassword  p where l.id=p.id and l.number="'.$data1.'" and l.phone="'.$data2.'"';
        
       $result = $mysql->getData($sql);

            

     $jsonre='[{"number":"'.$result[0]["number"].'","password":"'.$result[0]["password"].'"}]';
    
     $sql = 'update lucklist l set l.state=1 where l.number="'.$data1.'" and l.phone="'.$data2.'"';
        $mysql->runSql( $sql );

        $mysql->closeDb();
        

        echo $jsonre;
       
}


?>