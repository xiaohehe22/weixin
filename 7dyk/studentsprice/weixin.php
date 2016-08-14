
<?php
/**
 * wechat php test
 */

require("./config.php");
require("./myDB.php");
//require("./shop.func.php");

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();

class wechatCallbackapiTest {
    
    private $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";            
            
    private $picTpl  = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[news]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                        <item>
                        <Title><![CDATA[%s]]></Title> 
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                        </Articles>
                        <FuncFlag>1</FuncFlag>
                        </xml>";
   private $photoTpl = "<xml>
                        <ToUserName><![CDATA[toUser]]></ToUserName>
                        <FromUserName><![CDATA[fromUser]]></FromUserName>
                        <CreateTime>12345678</CreateTime>
                        <MsgType><![CDATA[image]]></MsgType>
                        <Image>
                        <MediaId><![CDATA[media_id]]></MediaId>
                        </Image>
                        </xml>";
   
    private $openid;
    private $platformid;
    
    
    /**
     *回复文本消息
     *@param $content string
     *@return null
     */
    private function replyText($content) {
    	  
          $resultStr = sprintf($this->textTpl, $this->openid, $this->platformid, time(), "text", $content);
          echo $resultStr;
    }
    
     /**
     *回复图文消息
     *@param $articles array  such as array(0=>array("title"=>"title", "description"=>"description", "picUrl"=>"picUrl", "url"=>"url"))
     *@return null
     */
    private function replyNews($articles) {
    	
        $newsHead   = "<xml>
                         <ToUserName><![CDATA[%s]]></ToUserName>
                         <FromUserName><![CDATA[%s]]></FromUserName>
                         <CreateTime>%s</CreateTime>
                         <MsgType><![CDATA[news]]></MsgType>
                         <ArticleCount>%s</ArticleCount>
                         <Articles>";
        
        $articleItem = "<item>
                         <Title><![CDATA[%s]]></Title> 
                         <Description><![CDATA[%s]]></Description>
                         <PicUrl><![CDATA[%s]]></PicUrl>
                         <Url><![CDATA[%s]]></Url>
                         </item>";
        
        $newsTail    = "</Articles>
                        <FuncFlag>1</FuncFlag>
                        </xml>";
     
                
    	
        
        if(count($articles) > 9) {
        	$articles = array_slice($articles, 0, 9);
        }
        
        $news        = sprintf($newsHead,$this->openid, $this->platformid, time(), count($articles));
        
        foreach($articles as $article) {
            $news =  $news . sprintf($articleItem, $article['title'], $article['description'], $article['picUrl'], $article['url']);
        }
          
        $news = $news . $newsTail;
        echo $news;
    }
    
     /**
     *回复图片消息
     *@param $articles array  such as array(0=>array("title"=>"title", "description"=>"description", "picUrl"=>"picUrl", "url"=>"url"))
     *@return null
     */
    private function replyImage($mediaid) {
       $resultStr = sprintf($this->photoTpl, $this->openid, $this->platformid, time(), $mediaid);
       echo $resultStr;
    }
    
    
    
    /**
     *验证消息
     *@return null
     */
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
    
    
    
    /**
     *消息回复
     *@return null
     */
    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj          = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername     = $postObj->FromUserName;
        	$toUsername       = $postObj->ToUserName;  
            
            $this->openid     = $fromUsername;
            $this->platformid = $toUsername;
            
            $msgType          = $postObj->MsgType;   
            
            switch($msgType) {
                case "event":
                	$this->eventLogic($postObj);
                	break;
                
                case "text":
                	$this->textLogic($postObj);                	                	
                	break;
                
                case "image":
                	//$this->imageLogic($postObj);
                	break;
                default:;
            }
            	
         		
            
         	 	

        }else {
        	echo "";
        	exit;
        }
    }
    
    
    /**
     *事件消息处理逻辑
     *@param $postObj object
     *@return null
     */
    private function eventLogic($postObj) {        
    	$ev           = $postObj->Event;
        $eventkey     = $postObj->EventKey;
        
        if ($ev == "subscribe") {
            $contentStr = "/:rose 么么哒，欢迎关注学生价\n 一起发现身边的优惠和精彩！";
            $this->replyText($contentStr);
        }
    }
    
    
    /**
     *文本消息处理逻辑
     *@param $postObj object
     *@return null
     */
    private function textLogic($postObj) {        
    	$keyword      = trim($postObj->Content);
     
        if(!empty( $keyword )) {	
            
            if(preg_match("/^报名/", $keyword)) {
                
                $resultArray = explode("+", $keyword);
                if(count($resultArray) < 2) {
                	$resultArray = explode("＋", $keyword);
                }
                if( count($resultArray) != 4 ) {
                    $contentStr = "您的报名格式不对，请回复“报名＋队名＋队员(队员用逗号分隔)＋联系方式”进行报名。";
                	$this->replyText($contentStr);
                }
                else {
                    $name    = trim($resultArray[1]); //队名
                    $members = trim($resultArray[2]); //队员
                    $contact = trim($resultArray[3]); //联系方式
                    $time    = time();
                    
                    $link    = mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
                    mysql_select_db(SAE_MYSQL_DB, $link);
                    
                    $sql     = "select count(*) as total from `team` where openid = '" . $this->openid . "'";
                    $res     = mysql_query($sql);
                    $row     = mysql_fetch_array($res);
                    $total   = $row['total'];
                    
                    if($total > 0) { //如果已存在报名信息，则更新报名信息
                        $sql = "update `team` set `name` = '$name', `members` = '$members', `contact` = '$contact', `update_time` = '$time' where `openid` = '" . $this->openid . "'";
                        $res = mysql_query($sql);
                        
                        if($res) {//更新报名信息成功  
                            $sql     = "select * from `team` where openid = '" . $this->openid . "'";
                            $res     = mysql_query($sql);
                            $row     = mysql_fetch_array($res);
                            $id      = $row['id'];
                            
                            $contentStr = "恭喜您，更新报名信息成功！\n您的队伍编号是：$id\n您的队名是：$name\n您的队员是：$members\n您的联系方式是：$contact\n\n若要更改报名信息，可以按相同格式进行回复\n回复【查看报名】查看报名信息\n回复【投票＋队伍编号】进行投票，例如：投票1\n回复【查看＋队伍编号】查看队伍投票结果，例如：查看1。";
                			$this->replyText($contentStr);
                        }
                        else {//更新报名信息失败                            
                            $contentStr = "对不起，更新报名信息失败！请您再次尝试或者联系客服。";
                			$this->replyText($contentStr);
                        }
                        
                        
                    }
                    else {//添加报名信息
                        
                        $sql = "insert into `team`(`openid`,`name`, `members`, `contact`, `create_time`, `update_time`) VALUES ('$this->openid','$name', '$members', '$contact', '$time', '$time')";
                        $res = mysql_query($sql);
                        
                        if($res) {//报名成功   
                            $sql     = "select * from `team` where openid = '" . $this->openid . "'";
                            $res     = mysql_query($sql);
                            $row     = mysql_fetch_array($res);
                            $id      = $row['id'];
                            
                            
                            $contentStr = "恭喜您，报名成功！\n您的队伍编号是：$id\n您的队名是：$name\n您的队员是：$members\n您的联系方式是：$contact\n\n若要更改报名信息，可以按相同格式进行回复\n回复【查看报名】查看报名信息\n回复【投票＋队伍编号】进行投票，例如：投票1\n回复【查看＋队伍编号】查看队伍投票结果，例如：查看1。";
                			$this->replyText($contentStr);
                        }
                        else {//报名失败                            
                            $contentStr = "对不起，报名失败！请您再次尝试或者联系客服。";
                			$this->replyText($contentStr);
                        }
                        
                        
                    }
                    mysql_close($link);
                }                                            
                
            } else if($keyword == "查看报名") {
                $link    = mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
                mysql_select_db(SAE_MYSQL_DB, $link);
                
                
                $sql     = "select * from `team` where openid = '" . $this->openid . "'";
                $res     = mysql_query($sql);
                $row     = mysql_fetch_array($res);
                if($row) {
                    $id      = $row['id'];
                    $name    = $row['name'];
                    $members = $row['members'];
                    $contact = $row['contact'];
                	$contentStr = "您的队伍编号是：$id\n您的队名是：$name\n您的队员是：$members\n您的联系方式是：$contact";
               		$this->replyText($contentStr);
                }
                else {
                	$contentStr = "您还没有报名信息，请回复“报名＋队名＋队员+联系方式”进行报名。";
               		$this->replyText($contentStr);
                }
                
                
               	mysql_close($link);
                
            } else if (preg_match("/^投票/", $keyword)) {
                
                $starttime = strtotime("2015-08-19 00:00:00") - time();
                $endtime = strtotime("2015-08-21 18:00:00") - time();
                
                if($starttime > 0) {
                    $contentStr = "亲，活动还没有开始!";
                } else if ($endtime < 0) {
                    $contentStr = "对不起，来晚啦，投票已经结束啦!";
                } else {
                    if(preg_match("/^投票(\d+)/",$keyword,$resultArray)){
                        
                        $link = mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
                        
                        if ($link) {
                            mysql_select_db(SAE_MYSQL_DB, $link);
                            $sql = "select * from `ever_voted` where username = '$this->openid'";
                            $res = mysql_query($sql);
                            $row = mysql_fetch_array($res);
                            
                            if (!$row) {
                                $sql = "select * from `team` where  id=" . $resultArray[1] ;
                                $res = mysql_query($sql);
                                $row = mysql_fetch_array($res);
                                if($row){
                                    $sql = "update `team` set vote = vote+1 where id=" . $resultArray[1];
                                    $res = mysql_query($sql);
                                    $row = mysql_fetch_array($res);
                                    
                                    $sql = "insert into `ever_voted` values ('$this->openid',1)" ;
                                    $res = mysql_query($sql);
                                    $row = mysql_fetch_array($res);
                                    $contentStr = " /:8-) 您给".$resultArray[1]."号投票成功！";                                                                                                                    
                                    $contentStr = $contentStr."\n /:8-)  回复 [查看n] 可以查看第n号选手的票数 \n";
                                }else{
                                	$contentStr = "抱歉, 该队伍不存在";
                                }
                                        
                           	} else {
                                $contentStr = " /:8-) 抱歉，您已经投过票了，每个人只能投一次票哦，快邀请小伙伴给您投票吧~ \n /:8-) 回复 [查看n] 可以查看第n号选手的票数";
                            }
                            mysql_close($link);
                        }
                   }else{
                   		$contentStr = "投票格式有误~ 请输入[投票 + 选手编号],例： 投票1";
                   }
            	}
               	$this->replyText($contentStr);
            } else if (preg_match("/^查看/", $keyword)){
                if(preg_match("/^查看(\d+)/",$keyword,$resultArray)){
                    $link = mysql_connect(SAE_MYSQL_HOST_M . ':' . SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
                    mysql_select_db(SAE_MYSQL_DB, $link);
                    $sql            = "select * from `team` where id=" . $resultArray[1];
                    $res            = mysql_query($sql);
                    $row            = mysql_fetch_array($res);
                    $candidate_name = $row['name'];
                    $vote_number    = $row['vote'];
                    
                    $tmp = "/:rose 您查询的队伍为：";
                    $contentStr = $tmp.$row['id']."号 \n /:8-) ta们目前有".$vote_number."票，";
                    
                    $sql  = "select (count(*)+1) as rank from `team` where vote > " . $vote_number;
                    $res  = mysql_query($sql);
                    $row  = mysql_fetch_array($res);		
                    $rank = $row['rank']; 
                    $contentStr = $contentStr."排在第".$rank."名 \n";
                    
                    if($rank != 1){                         
                        $sql  			  = "select * from `team` order by vote desc limit ".($rank-2).",1";
                        $res  			  = mysql_query($sql);
                        $row  			  = mysql_fetch_array($res);		
                        $prev_vote_number = $row['vote'];
                        $difference_value = $prev_vote_number - $vote_number;
                        $contentStr = $contentStr."/:8-)与第".($rank-1)."名相差".$difference_value."票\n 继续加油！";
                    }else{
                        $contentStr = $contentStr." 太厉害了，继续加油！";
                    }
                    
                    mysql_close($link);
                    $this->replyText($contentStr);
                }
                /* } else if(strpos($keyword, "小鲸鱼") != -1) {
            	$contentStr = '我是小鲸鱼，微信号是 mfjdxc，come on！加我！';
                $this->replyText($contentStr);
                */
            } else if(in_array($keyword, array("推荐", "有奖推荐"))) {
                $contentStr = 'http://mp.weixin.qq.com/s?__biz=MzA3NTU2NTYzOA==&mid=201101226&idx=1&sn=bf6fc70d57e4f41013561d76e3aa4e6f#rd';
                $this->replyText($contentStr);
            	
   			} else if(in_array($keyword, array("优惠券"))) {	
                $contentStr = '优惠券戳这里：http://m.5ikfc.com/';
                $this->replyText($contentStr);
            	
            } else if(in_array($keyword, array("9月"))) {	
                $contentStr = '9月优质活动戳这里：http://mp.weixin.qq.com/s?__biz=MzA3NTU2NTYzOA==&mid=213292508&idx=1&sn=4fb6441917bf3557dbad8a58689125c6#rd';
                $this->replyText($contentStr);
            	
            } 
            
            
            
            
            /*
            else{//接图灵机器人                
                                
                $apiKey = "857e7ce3c8695cc4d39c0edb2194def4"; 
                $info   = $keyword;
				$apiURL = "http://www.tuling123.com/openapi/api?key=$apiKey&info=$info";
                
                $res    = file_get_contents($apiURL); 
                $resObj = json_decode($res, true);
                
                switch($resObj['code']) {
                    case 100000:
                    	$contentStr = $resObj['text'];
                    	$contentStr = str_replace("图灵机器人", "小鲸鱼～", $contentStr);
                    	$this->replyText($contentStr);
	                    break;
                    case 200000:
                    	$contentStr = $resObj['text']." ".$resObj['url'];
                    	$this->replyText($contentStr);
	                    break;
                    case 302000:
                    	$articles = array();
                    	foreach($resObj['list'] as $item) {
                            $article['title'] = $item['article']."——".$item['source'];
                            $article['description'] = "";
                            $article['picUrl'] = $item['icon'];
                            $article['url'] = $item['detailurl'];
                    		array_push($articles, $article);
                    	}
                    
                    	$this->replyNews($articles);
	                    break;
                    case 305000:
                    	$articles = array();
                    	foreach($resObj['list'] as $item) {
                            $article['title'] = $item['trainnum'].$item['starttime']."——".$item['endtime'];
                            $article['description'] = "";
                            $article['picUrl'] = $item['icon'];
                            $article['url'] = $item['detailurl'];
                    		array_push($articles, $article);
                    	}
                    
                    	$this->replyNews($articles);
	                    break;
                    case 306000:
                    //$contentStr = "对不起，小图灵现在无法为你提供航班查询服务。\n靠，那我只能在这里做个广告了：订机票，上群哪儿！\nhttp://flight.qunar.com/";
                    //$this->replyText($contentStr);
                    
                    	$articles = array();
                    	foreach($resObj['list'] as $item) {
                            $article['title'] = "(".$item['starttime']."——".$item['endtime'].")".$item['flight'];
                            $article['description'] = "";
                            $article['picUrl'] = $item['icon'];
                            $article['url'] = $item['detailurl'];
                    		array_push($articles, $article);
                    	}
                    
                    	$this->replyNews($articles);
	                    break;
                    case 308000:
                    	$articles = array();
                    	foreach($resObj['list'] as $item) {
                            $article['title'] = $item['name'];
                            $article['description'] = '';
                            $article['picUrl'] = $item['icon'];
                            $article['url'] = $item['detailurl'];
                    		array_push($articles, $article);
                    	}
                    
                    	$this->replyNews($articles);
	                    break;
                    default:
                    	$contentStr = "小鲸鱼今天生病了，不能和你聊天了，呜呜～";
                    	$this->replyText($contentStr);
                    
                     
                }
        	}*/
              
        }else{
            echo "Input something...";
        }
        
    }
    
    
       

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}

?>