<?php
header("Content-type:text/html;charset=utf-8");

include_once ('functions.php');

define("TOKEN", "weixin");
define("appid","wxb19fa004abdf4374");
define("appsecret","82be22c5f63ac37bd59b528fe7482f9e");
define("access_token",get_access_token(appid,appsecret));
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
            $msgType= "text";
            $textTpl= "<xml>
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
                    if (isset($postObj->EventKey)){
                        $sourceId=str_replace("qrscene_","",$postObj->EventKey);
                        if(!isexistid($fromUsername)){
                            $contentStr='欢迎关注';
                            $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            echo $result;
                            break;
                        }
                        if(isexist_sourceid($sourceId)){
                            //$contentStr='测试2';
                            $contentStr=vote($fromUsername,$sourceId,$toUsername,access_token);
                            $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                            echo $result;
                        }
                        //$picurl=qrcode(access_token,$fromUsername);
                        //$result=sprintf($textTpl[1], $fromUsername, $toUsername, $time, $msgType[1], $picurl);
                       // echo $result;
                    }
                    break;
                case "unsubscribe":
                    break;
                case "CLICK":
                    break;
            }
            switch ($keywords){
                case "001":
                    $contentStr="恭喜你报名成功啦！视频直播会在8月18日周四晚上8点准时开始，我们会提前 10分钟在此服务号提醒你参加，请密切关注哦~如果你想要获得导师独家的PPT ，请回复【ppt1】";
                    send_text(access_token,$fromUsername,$contentStr);
                    break;
                case "ppt":
                    $contentStr='请分享下面的海报到朋友圈，并配文字“已报名”，截图发送到后台就可以啦';
                    $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $result;
                    $media_id=get_image(access_token);
                    send_image(access_token,$fromUsername,$media_id);
                break;
            }
            if($postObj->MsgType=="image"){
                $contentStr='小师兄收到啦，直播结束后PPT就会飞到你手中了';
                $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $result;
            }
        }else{
            echo "";
            exit;
        }
    }
}
?>
