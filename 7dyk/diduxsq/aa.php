<?php
/*
    方倍工作室 http://www.cnblogs.com/txw1958/
    CopyRight 2013 www.fangbei.org  All Rights Reserved
*/

function memberVote($memberid,$weixinid)
{
    $mysql = new SaeMysql();

    $sql = "select id from users where id='$memberid'";
    $result = $mysql->getData($sql);


    if($result==null){
        $mysql->closeDb();
        return  "好像没有这个id哦".$memberid;
    }


    $sql = "select id from votes where name='$weixinid'";
    $result = $mysql->getData($sql);


    if($result!=null){
        $mysql->closeDb();
        return  "你已经给投过票啦";
    }else{
        $sql="insert into votes values ('','$weixinid')";
        $mysql->runSql($sql);
    }


    $sql = "update users set vote = vote+1 where id='$memberid'";
    $mysql->runSql($sql);

    $mysql->closeDb();

    return  "投票给".$memberid."成功";

}



function showVote($memberid,$weixinid)
{
    $mysql = new SaeMysql();

    $sql = "select vote from users where id='$memberid'";
    //$sql = "select vote from user order by desc";
    $result = $mysql->getData($sql);
    $vote=$result[0]['vote'];

    if($result==null){
        $mysql->closeDb();
        return  "好像没有这个id哦".$memberid;
    }



    //$sql = "select m.id,m.membername,count(*) num from votemember m,voteinfo i where m.id=i.memberid group by m.id order by num desc";
    $sql = "select vote from users order by vote desc";
    $result = $mysql->getData($sql);
    if($result==null){
        $mysql->closeDb();
        return  "error";
    }
    $mysql->closeDb();
    //return $vote;
    // echo $memberid;

    $rank=1;
    $numtemp=$result[0]["vote"];
    $prenum=$numtemp;
    $num=$vote;


    $j=-1;

    for($i=0;$i<count($result);$i++)  //循环便利显示查询结果
    {
        $numtemp=$result[$i]["vote"];

        if($num!=$numtemp){
            $rank=$i+2;
            $prenum = $numtemp;
        }else{
            $j=$i;
            break;

        }
        //echo "i".$i;
        //echo "rank".$rank;
        //echo "prenum".$prenum;
        //echo "num".$num;
        //echo "m.id".$result[$i]["id"];

        //if($result[$i]["id"]==$memberid){
        //    $j=$i;
        //     break;
        // }
    }

    if($j==-1){
        return  "这个编号还没有人投过票哦";
    }

    $dis =$prenum-$num;
    //"这个编号名称是".$result[$j]["membername"]

    $result=$memberid."号当前票数为".$num."，当前排名：第".$rank."名，距离上一名".$dis."票\n";

    return  $result;
}






define("TOKEN", "weixin");


$wechatObj = new wechatCallbackapiTest();

if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keywords = trim($postObj->Content);
            $keyword=substr ( $keywords, 0, 6 );
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            $ev = $postObj->Event;
            if ($ev == "subscribe"){
                $msgType = "text";
                $contentStr = "欢迎关注7点一刻Openday！回复‘规则’了解详细信息，点击http://7dyk.applinzi.com/register.php/直接报名";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }


            if($keyword == "报名")
            {
                $msgType = "text";
                $contentStr =  '报名入口：http://7dyk.applinzi.com/register.php';
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword == "规则")
            {
                $msgType = "text";

                $contentStr = '回复“报名”，进入报名入口，填写报名表领取编号。
							   回复“支持+编号”，如“支持1”，拉小伙伴支持自己。
							   回复“查看+编号”，如“查看 1”，查看当前支持数及上一名差距。
							   支持数前50的小伙伴参加活动';
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword == "支持")
            {
                $msgType = "text";
                $contentStr = memberVote ( substr ( $keywords, 6 ), $fromUsername);
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
            if($keyword == "查看")
            {
                $msgType = "text";
                $contentStr = showvote ( substr ( $keywords, 6 ),  $fromUsername);
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }

        }else{
            echo "";
            exit;
        }
    }
}
?>
