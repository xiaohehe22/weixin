<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/4
 * Time: 14:45
 */

function db_connect(){
    @$result = new mysqli('101.200.85.218', 'root','7DYKdev123!@#','test');
    if (!$result){
        echo 'Could not connect to database server';
    }else{

        return $result;
    }
}

function filled_out($form_vars){
    foreach ($form_vars as $key=>$value){
        if ((!isset($key))||($value=='')){
            return false;
        }
    }
    return true;
}

//投票
function memberVote($memberid,$weixinid)
{
    $mysql = db_connect();


    //查看是否存在该id
    $vote =isexist($memberid);
    if($vote===false)
        return "好像没有这个id哦";


    //查看是否已经投票
    $sql = "select id from qixivote where name='$weixinid'";
    $result = $mysql->query($sql);
    if(!$result){
        return  'error';
    }else{
        $test=$result->fetch_array();
        if (count($test)==0){
            return  "你已经给投过票啦";
        }
        $sql="insert into qixivote(name) values ($weixinid')";
        $mysql->query($sql);
    }


    $sql = "update qixi set vote = vote+1 where id='$memberid'";
    $mysql->query($sql);
    $sql = "select vote from qixi where id='$memberid'";
    $result = $mysql->query($sql);
    $test=$result->fetch_array();
    $vote=$test[0];
    $rank=showrank($vote);
    mysqli_close($mysql);
    return  "成功支持".$memberid."号喜鹊一只,\n当前喜鹊数为".$vote."，当前排名：第".$rank[0]."名，距离上一名".$rank[1]."只喜鹊\n";

}


//查看票数
function showVote($memberid)
{
    $vote =isexist($memberid);
    if($vote===false)
        return "好像没有这个id哦";
    $rank=showrank($vote);
    $result=$memberid."号喜鹊一只,\n当前喜鹊数为\".$vote.\"，当前排名：第\".$rank[0].\"名，距离上一名\".$rank[1].\"只喜鹊\n";
    return  $result;
}


/*
 * 输入$vote为票数
 * 返回当前排名和前一名票数差
 */
function showrank($vote){
    $mysql = db_connect();
    $query= "select vote from qixi order by vote desc";
    $result=$mysql->query($query);
    mysqli_close($mysql);
    $test=$result->fetch_object();
    $pre=$test->vote;

    if($pre==$vote){    //第一名
        $results[0]=1; //存排名
        $results[1]=0; //存票差
    }else{
        for ($i=0;$row=$result->fetch_object();$i++){
            if($vote==$row->vote) {
                $results[0]=$i+2;
                break;
            }
            $pre=$row->vote;
        }
        $results[1]=$pre-$vote;
    }
    return $results;
}



//输入id，判断是否存在该id，存在返回票数，
function isexist($memberid){
    $mysql = db_connect();
    $sql = "select vote from qixi where id='$memberid'";
    $result = $mysql->query($sql);
    mysqli_close($mysql);
    if(!$result){
        return  'error';
    }else{
        $test=$result->fetch_array();
        if (count($test)==0){
            return false;
        }else{
            return $test[0];
        }
    }

}