<?php
header("Content-type:text/html;charset=utf-8");
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
                    $contentStr = '欢迎关注7点一刻！点击<a href="http://h5app.7dyk.com/qixi/register.php">七夕活动</a>报名参加七夕活动,回复规则了解详细信息';
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
                    回复“编号+手机号”，如“我的编号12345678910”，查看您的报名id。
                    收到喜鹊数前50的小伙伴可以获得七夕专属影票哦。';
                    break;

                case "七夕":
                    $msgType = "text";
                    $contentStr =  '被你发现7点一刻的七夕活动咯，想要搭建你自己的鹊桥，<a href="http://h5app.7dyk.com/qixi/register.php">点击马上参与</a>';
                    break;

                case "支持" :
                case "投票" :
                    $msgType = "text";
                    $contentStr = memberVote ( substr ( $keywords, 6 ), $fromUsername);
                    break;

                case "查看":
                case "查询":
                    $msgType = "text";
                    $contentStr = showVote ( substr ( $keywords, 6 ));
                    break;

                case "编号":
                    $msgType = "text";
                    $contentStr = showId ( substr ( $keywords, 6 ));
                    break;

                case "测试":
                    $msgType = "text";
                    $contentStr = '<a href="http://www.baidu.com">百度</a>';
                    break;
                default:
                    $msgType = "text";
                    $contentStr = '没有查询到关键字，回复“规则”了解近期活动';


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
