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
            $msgType = "text";
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
                    $contentStr="起点就在7点一刻\n大学生迈入社会职场的加油站\n关于未来，问问前辈\n回复“001”可以查看讲座视频直播《对于大学生来说，创业真的是个坑吗？》~";
                    $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $result;
                    break;
                case "unsubscribe":
                    break;
                case "CLICK":
                    switch ($postObj->EventKey)
                    {
                        case "menu1":
                            $record[0]=array(
                                'title' =>'观前街',
                                'description' =>'观前街位于江苏苏州市区，是成街于清朝时期的百年商业老街，街上老店名店云集，名声远播海内外...',
                                'picUrl' => '/bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000052&itemidx=1&sign=90518631fd3e85dd1fde7f77c04e44d5#wechat_redirect'
                            );

                            $record[1]=array(
                                'title' =>'平江路',
                                'description' =>'平江路位于苏州古城东北，是一条傍河的小路，北接拙政园，南眺双塔，全长1606米，是苏州一条历史攸久的经典水巷。宋元时候苏州又名平江，以此名路...',
                                'picUrl' => '\bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000056&itemidx=1&sign=ef18a26ce78c247f3071fb553484d97a#wechat_redirect'
                            );
                            $record[3]=array(
                                'title' =>'观前街',
                                'description' =>'观前街位于江苏苏州市区，是成街于清朝时期的百年商业老街，街上老店名店云集，名声远播海内外...',
                                'picUrl' => '/bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000052&itemidx=1&sign=90518631fd3e85dd1fde7f77c04e44d5#wechat_redirect'
                            );

                            $record[4]=array(
                                'title' =>'平江路',
                                'description' =>'平江路位于苏州古城东北，是一条傍河的小路，北接拙政园，南眺双塔，全长1606米，是苏州一条历史攸久的经典水巷。宋元时候苏州又名平江，以此名路...',
                                'picUrl' => '\bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000056&itemidx=1&sign=ef18a26ce78c247f3071fb553484d97a#wechat_redirect'
                            );
                            $resultStr = response_news($postObj,$record);
                            echo $resultStr;
                            break;
                        case "menu2":
                            $record[0]=array(
                                'title' =>'观前街',
                                'description' =>'观前街位于江苏苏州市区，是成街于清朝时期的百年商业老街，街上老店名店云集，名声远播海内外...',
                                'picUrl' => '/bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000052&itemidx=1&sign=90518631fd3e85dd1fde7f77c04e44d5#wechat_redirect'
                            );

                            $record[1]=array(
                                'title' =>'平江路',
                                'description' =>'平江路位于苏州古城东北，是一条傍河的小路，北接拙政园，南眺双塔，全长1606米，是苏州一条历史攸久的经典水巷。宋元时候苏州又名平江，以此名路...',
                                'picUrl' => '\bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000056&itemidx=1&sign=ef18a26ce78c247f3071fb553484d97a#wechat_redirect'
                            );
                            $record[3]=array(
                                'title' =>'观前街',
                                'description' =>'观前街位于江苏苏州市区，是成街于清朝时期的百年商业老街，街上老店名店云集，名声远播海内外...',
                                'picUrl' => '/bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000052&itemidx=1&sign=90518631fd3e85dd1fde7f77c04e44d5#wechat_redirect'
                            );

                            $record[4]=array(
                                'title' =>'平江路',
                                'description' =>'平江路位于苏州古城东北，是一条傍河的小路，北接拙政园，南眺双塔，全长1606米，是苏州一条历史攸久的经典水巷。宋元时候苏州又名平江，以此名路...',
                                'picUrl' => '\bg4.jpg',
                                'url' =>'http://mp.weixin.qq.com/mp/appmsg/show?__biz=MjM5NDM0NTEyMg==&appmsgid=10000056&itemidx=1&sign=ef18a26ce78c247f3071fb553484d97a#wechat_redirect'
                            );
                            $resultStr = response_news($postObj,$record);
                            echo $resultStr;
                            break;
                        case "menu3":
                            $record[0]=array(
                                'title' =>'【第一期】对于大学生来说，创业真的是个坑么？',
                                'description' =>'快来吧',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/live1.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000184&idx=1&sn=1155ab06d0ba797f21a7add91028abe3&scene=18#wechat_redirect'
                            );
                            $resultStr = response_news($postObj,$record);
                            echo $resultStr;
                            break;
                        case "menu4":
                            $record[0]=array(
                                'title' =>'关于7点一刻',
                                'description' =>'我们有一个很大的理想',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/aboutus.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000080&idx=1&sn=672f5190412c15831f567a776fb286dd&scene=18#wechat_redirect'
                            );
                            $resultStr = response_news($postObj,$record);
                            echo $resultStr;
                            break;

                    }
                    break;
                default:
                    break;
                    break;
            }
            switch ($keywords){
                case "001":
                    $contentStr=save_register($fromUsername);
                    send_text(access_token,$fromUsername,$contentStr);
                    break;
                case "ppt":
                    $contentStr=save_ppt($fromUsername);
                    $result=sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $result;
                    $media_id=get_image(access_token);
                    send_image(access_token,$fromUsername,$media_id);
                    break;
            }
            if($postObj->MsgType=="image"){
                $contentStr=save_image($fromUsername);
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
