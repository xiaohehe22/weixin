<?php
header("Content-Type:text/json;charset=utf-8");


$data=array(
array("name"=>"test101","num"=>101),
array("name"=>"test102","num"=>102),
array("name"=>"test103","num"=>103),
);



if($_SERVER["REQUEST_METHOD"]=="GET"){

    $name = $_GET["name"];
    getnum($name);
    
}else if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = $_GET["name"];
    addnum($name);
}


//获取点击记录
function getnum($name){
if(!isset($_GET["name"])||empty($_GET["name"])){
     echo '{"name":"no name"}';
return;
}
    if($name=='all'){
        $mysql = new SaeMysql();
        $sql = "select i.name,count(*) from visitinfo v,idinfo i where i.name=v.name and i.usestate=1 group by name";
        $result = $mysql->getData($sql);

        $jsonre =  '[';
        $sum=0;
       
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {
            $jsonre=$jsonre.'{"name":"'.$result[$i]["name"].'","num":"'.$result[$i]["count(*)"].'"},';
            $sum=$sum+$result[$i]["count(*)"];
         }   
        
        
        $jsonre = $jsonre .'{"name":"sumvisit","num":"'.$sum.'"}]';
        $mysql->closeDb(); 
        
        echo $jsonre;
        //    echo '{"name":"name","num":"111"}';
        
    }else{
        // $mysql = new SaeMysql();
        // $sql = "select count(*) from visitinfo where name='$name'";
        // $result = $mysql->getLine($sql);
        // $mysql->closeDb();   
        echo '{"name":"name","num":"111"}';
   
    }
}


//写入是个内容
function addnum($name){
    
if(!isset($_GET["name"])||empty($_GET["name"])){
     echo '{"name":"success1"}';
return;
}

$mysql = new SaeMysql();

$sql = "insert into visitinfo(name)values('$name')";
$mysql->runSql($sql);
//close db connection
$mysql->closeDb();
    
    
echo '{"name":"success2"}';

}
?>