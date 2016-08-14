<?php


class voteAction
{
    public function memberSigh($school,$grade,$major,$membername,$contacts,$phone){	

		$mysql = new SaeMysql();
        
        $sql = "select id from votemember where school='$school' and membername='$membername' and grade='$grade' and major='$major'";
        $result = $mysql->getData($sql);
        
        if($result!=null){
        $mysql->closeDb();
        return  $result[0]["id"]; 
        }
        


		$sql = "insert into votemember (school,grade,major,membername,contacts,phone) values ('$school','$grade','$major','$membername','$contacts','$phone')";
		$mysql->runSql($sql);  
        
        sleep(1);

        $sql = "select id from votemember where school='$school' and membername='$membername' and grade='$grade' and major='$major'";
        $result = $mysql->getData($sql);

        
		$mysql->closeDb();
    
		return  $result[0]["id"]; 
       
    }   
    
    public function memberInquire($memberid)
    {
        $mysql = new SaeMysql();   
        
        $sql = "select id from votemember where id='$memberid'";
		$result = $mysql->getData($sql);
        
        if($result==null){
        $mysql->closeDb();
        return  "你查询的编号不存在哦"; 
        }
        
		$sql = "select m.id,m.membername,count(*) num from votemember m,voteinfo i where m.id=i.memberid group by m.id order by num desc";
		$result = $mysql->getData($sql);
        $mysql->closeDb();

        // echo $memberid;
        
        $rank=1;
        $numtemp=$result[0]["num"];
        $prenum=$numtemp;
        $num=0;

        
        $j=-1;
        
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {   
            $num=$result[$i]["num"];
            
            if($num!=$numtemp){
            	$rank=$i+1;
                $prenum = $numtemp; 
                $numtemp = $num;
            }
            echo "i".$i;
            echo "rank".$rank;
            echo "prenum".$prenum;
            echo "num".$num;
            echo "m.id".$result[$i]["id"];
            
            if($result[$i]["id"]==$memberid){ 
                $j=$i;
                break;
            }           

         }  
        
        if($j==-1){
        return  "这个编号还没有人投过票哦"; 
        }
        
        $dis =$prenum-$num;
        //"这个编号名称是".$result[$j]["membername"]

        $result=$memberid."班级当前票数为".$num."，当前排名：第".$rank."名，距离上一名".$dis."票\n";
		
        return  $result; 
        
        
    }  
    
	public function memberVote($memberid,$weixinid)
    {
        $mysql = new SaeMysql();
        
        $sql = "select id from voteinfo where weixinid='$weixinid'";
		$result = $mysql->getData($sql);
  

        if($result!=null){
            $mysql->closeDb();
             return  "你已经给某个班级投过票啦"; 
        }
        
        $sql = "select id from votemember where id='$memberid'";
		$result = $mysql->getData($sql);
  

        if($result==null){
        $mysql->closeDb();
        return  "好像没有这个班级编号哦"; 
        }
        
        /*  $sql = "select count(*) num from voteinfo where memberid>1";
		$result = $mysql->getData($sql);
        $count = $result[0]["num"];
        if($count<2000){
        $sql = "select m.id,m.membername,count(*) num from votemember m,voteinfo i where m.id>1 and m.id=i.memberid group by m.id order by num desc";
		$result = $mysql->getData($sql);
        $class=$result[0]["num"];
        $sql = "select m.id,m.membername,count(*) num from votemember m,voteinfo i where m.id=1 and m.id=i.memberid group by m.id order by num desc";
		$result = $mysql->getData($sql);
        $my=$result[0]["num"];
if($my<750){
                $f=rand(1,7);
                for($i=0;$i<$f;$i++){
                  $sql = "insert into voteinfo(memberid,weixinid)values('1','test')";
                    $mysql->runSql($sql);  
                }   
            }
}*/

        
        
		$sql = "insert into voteinfo(memberid,weixinid)values('$memberid','$weixinid')";
		$mysql->runSql($sql);      
        
		$mysql->closeDb();
  
		return  "投票给".$memberid."号班级成功"; 
        
    }  
    
    
        public function memberSigh2($membername,$member1,$phone1,$member2,$phone2,$member3,$phone3,$member4,$phone4){	

		$mysql = new SaeMysql();

		$sql = "insert into votemember2 (membername,member1,phone1,member2,phone2,member3,phone3,member4,phone4) values ('$membername','$member1','$phone1','$member2','$phone2','$member3','$phone3','$member4','$phone4')";
		$mysql->runSql($sql);  
        
        sleep(1);

        $sql = "select id from votemember2 where phone1='$phone1' and phone2='$phone2' and phone3='$phone3' and phone4='$phone4'";
        $result = $mysql->getData($sql);

        
		$mysql->closeDb();
    
		return  $result[0]["id"]; 
       
    }   
    
    
     public function memberInquire2($memberid)
    {
        $mysql = new SaeMysql();   
        
        $sql = "select id from votemember2 where id='$memberid'";
		$result = $mysql->getData($sql);
        
        if($result==null){
        $mysql->closeDb();
        return  "你查询的班级不存在哦"; 
        }
        
		$sql = "select m.id,m.membername,count(*) num from votemember2 m,voteinfo2 i where m.id=i.memberid group by m.id order by num desc";
		$result = $mysql->getData($sql);
        $mysql->closeDb();

        // echo $memberid;
        
        $rank=1;
        $numtemp=$result[0]["num"];
        $prenum=$numtemp;
        $num=0;

        
        $j=-1;
        
        for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
        {   
            $num=$result[$i]["num"];
            
            if($num!=$numtemp){
            	$rank=$i+1;
                $prenum = $numtemp; 
                $numtemp = $num;
            }
            echo "i".$i;
            echo "rank".$rank;
            echo "prenum".$prenum;
            echo "num".$num;
            echo "m.id".$result[$i]["id"];
            
            if($result[$i]["id"]==$memberid){ 
                $j=$i;
                break;
            }           

         }  
        
        if($j==-1){
        return  "你们的蜡像馆小组还没有人投过票哦"; 
        }
        
        $dis =$prenum-$num;

        $result="您的蜡像馆小组名称是".$result[$j]["membername"]."，当前票数为".$num."，当前排名：第".$rank."名，距离上一名".$dis."票";
		
        return  $result; 
        
        
    }  
    
    
    	public function memberVote2($memberid,$weixinid)
    {
        $mysql = new SaeMysql();
        
        $sql = "select id from voteinfo2 where weixinid='$weixinid'";
		$result = $mysql->getData($sql);
  

        if($result!=null){
        $mysql->closeDb();
        return  "你已经给蜡像馆小组投过票啦"; 
        }
        
        $sql = "select id from votemember2 where id='$memberid'";
		$result = $mysql->getData($sql);
  

        if($result==null){
        $mysql->closeDb();
        return  "好像没有蜡像馆小组是这个编号哦"; 
        }
        
        
		$sql = "insert into voteinfo2 (memberid,weixinid)values('$memberid','$weixinid')";
		$mysql->runSql($sql);      
        
		$mysql->closeDb();
    
		return  "投票给蜡像馆小组".$memberid."成功"; 
        
    }  
      
    
    
    
}
?>