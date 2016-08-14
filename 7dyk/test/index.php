<?php
header("Content-type:text/html;charset=utf-8");
define("TOKEN", "weixin");
define("access_token","XPKLmnOLxBGQlXG0VY6SUaiYh04akn9gANY5VCT89HPpPd4BcFo57GgXBd7NGVJa8qjcbPdR-EhBWTie-6epSDqZlIS5ZP0on9Cb-wvfctgDFMaAIAHJQ");

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
            $msgType[0] = "text";
            $textTpl[0] = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            $msgType[1]= "image";
            $textTpl[1] = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                         <Image><MediaId><![CDATA[%s]]></MediaId></Image>
                        <FuncFlag>0</FuncFlag>
                        </xml>";

            switch ($postObj->Event)
            {
                case "subscribe":
                    if (isset($postObj->EventKey)){
                        $sourceId=str_replace("qrscene_","",$postObj->EventKey);
                        if(!isexist($fromUsername)){
                            $contentStr='测试1';
                            $result=sprintf($textTpl[0], $fromUsername, $toUsername, $time, $msgType[0], $contentStr);
                            echo $result;
                            break;
                        }
                        if(isexist($sourceId)){
                            //$contentStr='测试2';
                            $contentStr=vote($fromUsername,$sourceId,$toUsername,access_token);
                            $result=sprintf($textTpl[0], $fromUsername, $toUsername, $time, $msgType[0], $contentStr);
                            echo $result;
                        }
                    }
                    $picurl=qrcode(access_token,$fromUsername);
                    $result=sprintf($textTpl[1], $fromUsername, $toUsername, $time, $msgType[1], $picurl);
                    echo $result;
                    break;
                case "unsubscribe":
                    break;
                case "CLICK":
                    break;
            }
            switch (substr($keywords,0,6)){
                case "测试":
                    $picurl=qrcode(access_token,$fromUsername);
                    $result=sprintf($textTpl[1], $fromUsername, $toUsername, $time, $msgType[1], $picurl);
                    echo $result;
                    break;
            }
        }else{
            echo "";
            exit;
        }
    }
}
?>
