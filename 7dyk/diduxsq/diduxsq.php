<?php
include 'vote.php';
define ( "TOKEN", "weixin" );
$wechatObj = new wechatCallbackapiTest ();
if (isset ( $_GET ['echostr'] )) {
	$wechatObj->valid ();
} else {
	$wechatObj->responseMsg ();
}
class wechatCallbackapiTest {
	public function valid() {
		$echoStr = $_GET ["echostr"];
		if ($this->checkSignature ()) {
			header ( 'content-type:text' );
			echo $echoStr;
			exit ();
		}
	}
	private function checkSignature() {
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
		
		$token = TOKEN;
		$tmpArr = array (
				$token,
				$timestamp,
				$nonce 
		);
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	
	// 响应消息
	public function responseMsg() {
		$postStr = $GLOBALS ["HTTP_RAW_POST_DATA"];
		if (! empty ( $postStr )) {
			
			$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
			
			$RX_TYPE = trim ( $postObj->MsgType );
			
			switch ($RX_TYPE) {
				case "event" :
					$result = $this->receiveEvent ( $postObj );
					break;
				case "text" :
					$result = $this->receiveText ( $postObj );
					break;
				default :
					$result = "new type";
					break;
			}
			
			echo $result;
		} else {
			echo "";
			exit ();
		}
	}
	
	// 接收事件消息
	private function receiveEvent($object) {
		$content = "";
		switch ($object->Event) {
			case "subscribe" :
				$content = "欢迎关注~~\n回复【规则】看看我们最近准备的活动吧。\n回复【侦探】查看侦探故事破解真相";
				break;
			case "unsubscribe" :
				$content = "取消关注";
				break;
			default :
				$content = "receive a new event: " . $object->Event;
				break;
		}
		if (is_array ( $content )) {
			$result = $this->transmitNews ( $object, $content );
		} else {
			$result = $this->transmitText ( $object, $content );
		}
		return $result;
	}
	
	// 接收文本消息
	private function receiveText($object) {
		$keyword = trim ( $object->Content );
        
        
        
		
		switch (substr ( $keyword, 0, 6 )) {            
            case "侦探" :
				    $content = array ();
					$content [] = array (
							"Title" => "（内附解答）侦探推理故事合辑，回复对应数字查看",
							"Description" => "别轻易看答案哦，你来做一回夏洛克吧~",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcGoONDiaiaX4dKP3phMtxnWMQzcic651hyW1QbQyib8GvhCb7PcAhKv1rORiagEbzo8pzCLelGZu7MRU0g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401344448&idx=1&sn=69bf316e9ea304940b9eb5242f15d2ec#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
				break;
			case "规则" :
            case "团建" :
				if (time () < strtotime ( '2016-01-08 24:00:00' )) {
					$content = array ();
					$content [] = array (
							"Title" => "我出钱，你团建！",
							"Description" => "快来为团队赢取千元大奖吧",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/6BdvMhKy9hmR6ka82fRp6VrW1CibWgicyjunHIPJciak0FtcoOiawYO33rmictG0ofss9oo66hFBRdMzYFAjZWoybsQ/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401301440&idx=1&sn=43079944061c49207008d9c4bacd394a#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
				} else {
					$content = "我出钱，你团建活动已经结束啦";
					$result = $this->transmitText ( $object, $content );
				}
				break;
			case "报名" :
				if (time () < strtotime ( '2016-01-08 24:00:00' )) {
                    $content = "想为团队赢取千元大奖？填写下报名信息吧~<a href=\"http://2.diduxsq.sinaapp.com/class_register.php\">戳这里</a>";
				} else {
					$content = "我出钱，你团建活动已经结束啦";
				}
				$result = $this->transmitText ( $object, $content );
				break;
			case "投票" :
				$action = new voteAction ();
				if (time () < strtotime ( '2016-01-08 24:00:00' )) {
					$content = $action->memberVote ( substr ( $keyword, 6 ), $object->FromUserName );
				} else {
					$content = "我出钱，你团建活动已经结束啦";
				}				
				$result = $this->transmitText ( $object, $content );
				break;
			case "查询" :
			case "查看" :
				$action = new voteAction ();
				if (time () < strtotime ( '2016-01-08 24:00:00' )) {
					$content = $action->memberInquire ( substr ( $keyword, 6 ) );
				}  else {
					$content = "我出钱，你团建活动已经结束啦";
				}				
				$result = $this->transmitText ( $object, $content );
				break;
			default :
                switch ($keyword) { 
                     case "0106" :
                     	$content = array ();
						$content [] = array (
							"Title" => "【侦探推理】 野餐杀机，看你能否三秒内识破凶手的谎言",
							"Description" => "四个高中生马克、威廉、汤姆和约翰一起到森林里去野餐。由于头一天下了雨，无法骑自行车，他们步行到了野餐地点。",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcGoONDiaiaX4dKP3phMtxnWMQzcic651hyW1QbQyib8GvhCb7PcAhKv1rORiagEbzo8pzCLelGZu7MRU0g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401343652&idx=1&sn=9fe7984785bd35c30a13ae970a21fa9e#rd" 
						);
						$result = $this->transmitNews ( $object, $content );
					break;
                    case "0107" :
                      	$content = array ();
						$content [] = array (
							"Title" => "不靠岸的神秘游轮，你能否揭穿小偷谎言？",
							"Description" => "一艘航海归来的英国轮船停在港口，但船上没有船员下船，原来在即将进港的时候，船长去了一趟甲板，回来的时候发现他保险柜里的一幅名画不见了。",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcGoONDiaiaX4dKP3phMtxnWMQzcic651hyW1QbQyib8GvhCb7PcAhKv1rORiagEbzo8pzCLelGZu7MRU0g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401343652&idx=2&sn=0eb548c82ff2841ddb35c3bc2c18c3ca#rd" 
						);
						$result = $this->transmitNews ( $object, $content );
					break;
                    case "0108" : 
                       	$content = array ();
						$content [] = array (
							"Title" => "【侦探推理】婴儿的眼泪",
							"Description" => "不久前，某市公安局刑侦大队接到匿名举报，有个代号为“飞狼”的拐卖婴儿的犯罪团伙，近日准备将一批婴儿移往粤闽交界的偏僻山村换取现钞。",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcGoONDiaiaX4dKP3phMtxnWMQzcic651hyW1QbQyib8GvhCb7PcAhKv1rORiagEbzo8pzCLelGZu7MRU0g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401343652&idx=3&sn=8217b66c8434c8a0c872254f3c5a6e27#rd" 
						);
						$result = $this->transmitNews ( $object, $content );
					break;
                    case "0109" : 
                    	$content = array ();
						$content [] = array (
							"Title" => "【侦探推理】十三个祭品，让人寒意顿生的推理难题",
							"Description" => "我们一行25人被困在一座小岛。",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcGoONDiaiaX4dKP3phMtxnWMQzcic651hyW1QbQyib8GvhCb7PcAhKv1rORiagEbzo8pzCLelGZu7MRU0g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401343652&idx=4&sn=e139772deb857572cdb44dd41699f0b1#rd" 
						);
						$result = $this->transmitNews ( $object, $content );
					break;
                    case "0110" :
                    $content = array ();
						$content [] = array (
							"Title" => "【侦探推理】 一个米奇书包引发的悲剧",
							"Description" => "邻居家的孩子是一个盲人，由于先天缺陷，使得他对一切都非常敏感。",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcGoONDiaiaX4dKP3phMtxnWMQzcic651hyW1QbQyib8GvhCb7PcAhKv1rORiagEbzo8pzCLelGZu7MRU0g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401343652&idx=5&sn=2e67c0f070ba21050edbc5c0f300c961#rd" 
						);
						$result = $this->transmitNews ( $object, $content );
					break;
                    case "0111" :
                    case "0112" :
                    case "0113" :
                     	$content = "今天的侦探故事还没有哦";
						$result = $this->transmitText ( $object, $content );
					break;
                    default:
                    	$content = "收到消息啦，我们会尽快回复~\n回复【规则】看看我们最近准备的活动吧。\n回复【侦探】查看侦探故事破解真相";
						$result = $this->transmitText ( $object, $content );
					break;                
                }
				break;
		}
		return $result;
	}
    
    	
	// 回复文本消息
	private function transmitText($postObj, $content) {
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$time = time ();
		$textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
		$msgType = "text";
		$result = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $content );
		
		return $result;
	}
	
	// 回复图文消息
	private function transmitNews($object, $newsArray) {
		if (! is_array ( $newsArray )) {
			return;
		}
		$itemTpl = "    <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
    </item>
";
		$item_str = "";
		foreach ( $newsArray as $item ) {
			$item_str .= sprintf ( $itemTpl, $item ['Title'], $item ['Description'], $item ['PicUrl'], $item ['Url'] );
		}
		$newsTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<Content><![CDATA[]]></Content>
<ArticleCount>%s</ArticleCount>
<Articles>
$item_str</Articles>
</xml>";
		
		$result = sprintf ( $newsTpl, $object->FromUserName, $object->ToUserName, time (), count ( $newsArray ) );
		return $result;
	}
	
	// 回复图片消息
	private function transmitImage($object) { // irU924xhACd43a5fnQRfKbJ29eJGpHZAzLWfYfxZdWSoKtKSWxVzbOv3yTReDG3X
		$itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";
		
		$item_str = sprintf ( $itemTpl, "8-0W5mVYNn1_5_3FcGyb7AV-BlUxMKCra-kBB2m_LxE" );
		
		$textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";
		
		$result = sprintf ( $textTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}
}

?>