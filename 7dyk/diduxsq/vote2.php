<?php


class voteAction2
{
   
    
        public function memberSigh($membername,$type,$phone){	

		$mysql = new SaeMysql();            
            
            
        $sql = "select id from votemember2 where phone='$phone'";
        $result = $mysql->getData($sql);
        
        if($result!=null){
        $mysql->closeDb();
        return  $result[0]["id"]; 
        }
        

		$sql = "insert into votemember2 (membername,type,phone) values ('$membername','$type','$phone')";
		$mysql->runSql($sql);  
        
        sleep(1);

        $sql = "select id from votemember2 where phone='$phone'";
        $result = $mysql->getData($sql);

        
		$mysql->closeDb();
    
		return  $result[0]["id"]; 
       
    }   
    
    
     public function memberInquire($memberid)
    {
        $mysql = new SaeMysql();   
        
        $sql = "select id from votemember2 where id='$memberid'";
		$result = $mysql->getData($sql);
        
        if($result==null){
        $mysql->closeDb();
        return  "你查询的编号不存在"; 
        }
        
		$sql = "select m.id,m.membername,m.type,count(*) num from votemember2 m,voteinfo2 i where m.id='$memberid' and m.id=i.memberid group by m.id";
		$result = $mysql->getData($sql);
        $mysql->closeDb();

        // echo $memberid;
                  
         if(count($result)==0){         
         return  "还没有人支持过你哦"; 
         }
         
        $flows = array("yd"=>array("0M","10M","30M","70M","150M","500M","1G","没有了"),"lt"=>array("0M","20M","50M","100M","200M","500M","1G","没有了"),"dx"=>array("0M","10M","30M","100M","200M","500M","1G","没有了"));
        $num=$result[0]["num"];
        $type=$result[0]["type"];
        $membername=$result[0]["membername"];
        $nextlevel = 1;
        $dis =-1;

if($num<10){
$nextlevel = 1;
$dis = 10-$num;
}else if($num>=10&&$num<20){
$nextlevel = 2;
$dis = 20-$num;
}else if($num>=20&&$num<40){
$nextlevel = 3;
$dis = 40-$num;
}else if($num>=40&&$num<60){
$nextlevel = 4;
$dis = 60-$num;
}else if($num>=60&&$num<80){
$nextlevel = 5;
$dis = 80-$num;
}else if($num>=80&&$num<100){
$nextlevel = 6;
$dis = 100-$num;
}else if($num>=100){
return $membername."童鞋,你已经获得".$num."个支持,恭喜拿到最高流量1G,太厉害啦";
}else{
return  "还没有人支持过你哦"; 
}

$nextflow = $flows[$type][$nextlevel];
         $flow = $flows[$type][$nextlevel-1];

$result=$membername."童鞋,你获得了".$num."个支持,已收获流量".$flow.",距离下一目标".$nextflow."还需要".$dis."个支持哦,继续加油吧";

		
        return  $result; 
        
        
    }  
    
    
    	public function memberVote($memberid,$weixinid)
    {
        $mysql = new SaeMysql();
        
        $sql = "select id from voteinfo2 where weixinid='$weixinid'";
		$result = $mysql->getData($sql);
  

        if($result!=null){
        $mysql->closeDb();
        return  "你已经支持过这个童鞋啦"; 
        }
        
        $sql = "select id from votemember2 where id='$memberid'";
		$result = $mysql->getData($sql);
  

        if($result==null){
        $mysql->closeDb();
        return  "好像没有童鞋是这个编号哦"; 
        }
        
        
		$sql = "insert into voteinfo2 (memberid,weixinid)values('$memberid','$weixinid')";
		$mysql->runSql($sql);      
        
		$mysql->closeDb();
    
		return  "支持童鞋".$memberid."成功"; 
        
    }  
      
    
    
    
}
?>