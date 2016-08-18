<?php
header("Content-type:text/html;charset=utf-8");

include_once ('functions.php');

define("TOKEN", "weixin");
define("appid","wxfbe89421611bdb2d");
define("appsecret","cd9c70575b4895cf7e810dce18bd47bd");
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
                                'title' =>'“微信之父”张小龙推荐的8本书',
                                'description' =>'“微信之父”张小龙推荐的8本书',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book1.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000185&idx=1&sn=8f09ce5f7c483a2977d36b438e504253&scene=20#wechat_redirect'
                            );

                            $record[1]=array(
                                'title' =>'原麦山丘市场总监：给市场营销专业推荐的9本书',
                                'description' =>'原麦山丘市场总监：给市场营销专业推荐的9本书.',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book2.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000185&idx=2&sn=5b78477841cadcfe0173a94b6b77e6b7&scene=20#wechat_redirect'
                            );
                            $record[3]=array(
                                'title' =>'腾讯PM：互联网产品经理精选7本工作必读书',
                                'description' =>'腾讯PM：互联网产品经理精选7本工作必读书',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book3.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000185&idx=3&sn=931d7bdf734c59047277a30290d67a42&scene=20#wechat_redirect'
                            );

                            $record[4]=array(
                                'title' =>'原京东HR推荐||求职必读的7本书',
                                'description' =>'原京东HR推荐||求职必读的7本书',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book4.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000185&idx=4&sn=5f718b3d3652d31b027d2714422eafef&scene=20#wechat_redirect'
                            );
                            $resultStr = response_news($postObj,$record);
                            echo $resultStr;
                            break;
                        case "menu2":
                            $record[0]=array(
                                'title' =>'求职互联网名企？肯定要看他们的书',
                                'description' =>'求职互联网名企？肯定要看他们的书',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book5.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000205&idx=1&sn=a603bbaf4c3258a484df3ea7609be197&scene=1&srcid=08163iV2O2ohSTSjqevLFGcO#wechat_redirect'
                             );

                            $record[1]=array(
                                'title' =>'跳出现在的生活，这7本书给你新的思维',
                                'description' =>'跳出现在的生活，这7本书给你新的思维',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book6.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000205&idx=2&sn=01886eaa8a592b72f0c51993f0cf505c&scene=1&srcid=08166oLVgwFKK77QrJ1HFQMr#wechat_redirect'
                                );
                            $record[3]=array(
                                'title' =>'想了解互联网行业？看这几本书就够了',
                                'description' =>'想了解互联网行业？看这几本书就够了',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book7.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000205&idx=3&sn=6bf52c6db431d1221d11c4a937bf20fe&scene=1&srcid=0816T09PytcqaI7NCSMQIXw0#wechat_redirect'
                            );

                            $record[4]=array(
                                'title' =>'面试不会说？提升口才的7本书',
                                'description' =>'面试不会说？提升口才的7本书',
                                'picUrl' => 'http://h5app.7dyk.com/chencong/test3/book8.jpg',
                                'url' =>'http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000205&idx=4&sn=a28f596a47bad275c93254fd7089fc82&scene=1&srcid=0816ze2tqzm2MsaqTZC3bxOp#wechat_redirect'
                            );
                            $resultStr = response_news($postObj,$record);
                            echo $resultStr;
                            break;
                        case "menu3":
                            $record[0]=array(
                                'title' =>'对于大学生来说，创业真的是个坑么？',
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
