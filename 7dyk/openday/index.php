<?php

define("TOKEN", "weixin");

include_once ('functions.php');
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
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";

            switch ($postObj->Event)
            {
                case "subscribe":
                    $msgType = "text";
                    $contentStr = '欢迎关注7点一刻！点击<a href="http://h5app.7dyk.com/qixi/register.php">七夕活动</a>,报名参加七夕活动';
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                    break;
                case "unsubscribe":
                    break;
                case "CLICK":
                    switch ($postObj->EventKey)
                    {

                    }
                    break;
                default:
                    break;
            }
            switch (substr($keywords,0,6)){
                case "规则":
                    $msgType = "text";

                    $contentStr = '回复“七夕”，进入7点一刻七夕节活动入口，填写报名表领取编号。 
                    回复“支持+编号”，如“支持1”，拉小伙伴为自己搭鹊桥。
                    回复“查看+编号”，如“查看 1”，查看当前喜鹊数及上一名差距。 
                    
                    收到喜鹊数前50的小伙伴可以获得七夕专属影票哦。';
                break;

                case "七夕":
                    $msgType = "text";
                    $contentStr =  '报名入口：<a href="http://h5app.7dyk.com/qixi/register.php">七夕活动</a>回复规则了解详细信息';
                break;

                case "支持" :
                case "投票" :
                    $msgType = "text";
                    $contentStr = memberVote ( substr ( $keywords, 6 ), $fromUsername);
                break;

                case "查看":
                case "查询":
                    $msgType = "text";
                    $contentStr = showvote ( substr ( $keywords, 6 ));
                break;

                case "我的编号":
                    $msgType = "text";
                    $contentStr = showid ( substr ( $keywords, 6 ));
                break;

                case "测试":
                    $msgType = "text";
                    $contentStr = '<a href="http://www.baidu.com">百度</a>';
                break;

            }
            $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $result;
        }else{
            echo "";
            exit;
        }
    }
}
?>
