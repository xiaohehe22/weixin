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
				$content = "欢迎关注~~先回复【规则】看看我们最近准备的活动吧。";
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
				$content = "收到消息啦，我们会尽快回复~回复【规则】，看看我们最新的活动吧";
				$result = $this->transmitText ( $object, $content );
				break;
		}
		return $result;
	}
	private function vote($object, $type, $num) {
		switch ($type) {
			
			case "投票" :
				$action = new voteAction ();
				$content = $action->memberVote ( $num, $postObj->FromUserName );
				// $result = $this->transmitText($object, $content);
				$result = $this->transmitImage ( $object );
				break;
			case "查询" :
				// $content = "查询成功".$num;
				$action = new voteAction ();
				$content = $action->memberInquire ( $num );
				$result = $this->transmitText ( $object, $content );
				break;
			default :
				$content = $num;
				$result = $this->transmitText ( $object, $content );
				break;
		}
		
		return $result;
	}
	
	// 回复文本消息
	private function transmitText($postObj, $content) {
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
		$time = time ();
		$textTpl = "FuncFlag>0</FuncFlag>
              <xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <          </xml>";
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