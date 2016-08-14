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
				$content = "欢迎关注不一样生活！\n带你认识不一样的人，过不一样的生活。\n回复【详情】，帮你报销火车票！\n回复【lx+省份】，认识你的帅哥美女老乡~~";
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
       $pros = array (							
        "安徽"=>"ukYZEPTGfgWS6-kX1DdCtWKEhhGOxnl_p9mtp7dhZzc",
        "重庆"=>"ukYZEPTGfgWS6-kX1DdCtR-LegE5aqW-ve5eaCqv9ho",
        "福建"=>"ukYZEPTGfgWS6-kX1DdCtSTfWOBy2xvYxQ8HmQBehFM",
        "甘肃"=>"ukYZEPTGfgWS6-kX1DdCtcBacOXJ_NNVvMejXRj2jhQ",
        "广西"=>"ukYZEPTGfgWS6-kX1DdCtRSLsuOsZTV-mNZg638n9Jo",
        "贵州"=>"ukYZEPTGfgWS6-kX1DdCtfsjHQvCJ1YQvdYnm8UYA6c",
        "海南"=>"ukYZEPTGfgWS6-kX1DdCtfwikPb74BU0ROLZt9LjiB4",
        "河北"=>"ukYZEPTGfgWS6-kX1DdCta6O2H-rMJrTFsVc_GPEthA",
        "黑龙"=>"ukYZEPTGfgWS6-kX1DdCtSpLx4au6o6HszqprdMNoCM",
        "河南"=>"ukYZEPTGfgWS6-kX1DdCtaC18MDUvuHSfO67RWxbU-w",
        "湖北"=>"ukYZEPTGfgWS6-kX1DdCtaF4-d2idHP5-XeBEzHy9eM",
        "湖南"=>"ukYZEPTGfgWS6-kX1DdCtQtdqk2YSjmz3D-H6Bygg94",
        "江苏"=>"ukYZEPTGfgWS6-kX1DdCtQTOBtPIsi8CsXeMncAFjus",
        "江西"=>"ukYZEPTGfgWS6-kX1DdCteG2EmxKYFWqA2CtyjG-D7s",
        "吉林"=>"ukYZEPTGfgWS6-kX1DdCtYUPSiNrSjR6feS30VQCywg",
        "辽宁"=>"ukYZEPTGfgWS6-kX1DdCtVK6kJC20G-xBNooqjZLt_Y",
        "陕西"=>"ukYZEPTGfgWS6-kX1DdCtW2uZEJC_0y4wDX5_2YPx7M",
        "内蒙"=>"ukYZEPTGfgWS6-kX1DdCtZ0Z8vYuHq7ocJ4BdI1M7-M",
        "宁夏"=>"ukYZEPTGfgWS6-kX1DdCtbdUQwBdTrdDaxdd_m6_Vc0",
        "青海"=>"ukYZEPTGfgWS6-kX1DdCtVj2F-E2OMcnbI1Aq6v4JT8",
        "山东"=>"ukYZEPTGfgWS6-kX1DdCtZkjEMQ5NmliOfBaVlC_oik",
        "上海"=>"ukYZEPTGfgWS6-kX1DdCtXOFL16EmTtFJbr3Vfvn71I",
        "山西"=>"ukYZEPTGfgWS6-kX1DdCtWN6kBD6wplg6pR_IeYietw",
        "四川"=>"ukYZEPTGfgWS6-kX1DdCtYgTDPivzZn5cBAbL_zcK7Y",
        "天津"=>"ukYZEPTGfgWS6-kX1DdCtdk4RQc28HPkltpeX8rmEQ0",
        "新疆"=>"ukYZEPTGfgWS6-kX1DdCtZe_5fNk9O-ElAl5c7yN3vs",
        "西藏"=>"ukYZEPTGfgWS6-kX1DdCtcqjiOC7s4n_mLZ9TI04NvY",
        "云南"=>"ukYZEPTGfgWS6-kX1DdCtYLiZhn1Z-b3988c3MoOi6E"
	);
		$keyword = trim ( $object->Content );
		
		switch (substr ( $keyword, 0, 6 )) {
			case "规则" :
			case "详情" : 
				if (time () < strtotime ( '2016-01-15 12:00:00' )) {
					$content = array ();
					$content [] = array (
							"Title" => "抢火车票！抢VIP！活动详情！",
							"Description" => "想获奖就来好好读规则！！！",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/seBf2kv8emt8q1PoYJ1stFANL826MhibXK6OeapaToib45Po7Q8oIlnFSt9fSM0pTnw2YCrzDnMMElwd2W93Ribjg/0?wx_fmt=png",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIyMjEyMjAxNA==&mid=401787994&idx=1&sn=f26891ad2264f86a3008f32288fb4343&scene=1&srcid=0111mvduDC0c01scKn1InLyo" 
					);
					$result = $this->transmitNews ( $object, $content );
				} else {
					$content = "抢火车票活动已经结束啦";
					$result = $this->transmitText ( $object, $content );
				}
				break;
             case "火车" :
				$action = new voteAction ();
				if (time () < strtotime ( '2016-01-15 12:00:00' )) {                    
                    $content = $action->memberSigh("school","grade","major",$object->FromUserName,"contacts","phone");
                    
                    
                    
                    
                    
                    
				} else {
					$content = "抢火车票活动已经结束啦";
				}				
				$result = $this->transmitText ( $object, "恭喜参与成功！你的编号是：".$content."\n回复“投票+编号”，可进行投票；\n回复“查看+编号”，可查看票数；\n回复“详情”，可查看活动详情；\n回复“lx+省份”，可加入老乡群！\n加油哦~ 票数前3名可以获得报销机会！\n
10票以上可获得爱奇艺和腾讯视频VIP！\n活动截止到1月15日12:00");
				break;
			case "投票" :
				$action = new voteAction ();
				if (time () < strtotime ( '2016-01-15 12:00:00' )) {
                    if(substr ( $keyword, 6, 1 )=="+"){
                    $content = $action->memberVote ( substr ( $keyword, 7 ), $object->FromUserName );
                    }else{
                    $content = $action->memberVote ( substr ( $keyword, 6 ), $object->FromUserName );
                    }					
				} else {
					$content = "抢火车票活动已经结束啦";
				}				
				$result = $this->transmitText ( $object, $content );
				break;
			case "查询" :
			case "查看" :
				$action = new voteAction ();
				if (time () < strtotime ( '2016-01-15 12:00:00' )) {
					 if(substr ( $keyword, 6, 1 )=="+"){
                   $content = $action->memberInquire ( substr ( $keyword, 7 ) );
                    }else{
                      $content = $action->memberInquire ( substr ( $keyword, 6 ) );
                    }
				}  else {
					$content = "抢火车票活动已经结束啦";
				}				
				$result = $this->transmitText ( $object, $content );
				break;
			default :
            switch (substr ( $keyword, 0, 2 )) { 
                case "lx":
                 if(!empty($pros[substr ( $keyword, 3 )])){                               
                 $content = $pros[substr ( $keyword, 3 )];
                 $result = $this->transmitImage( $object, $content );   
                 }else if(!empty($pros[substr ( $keyword, 2 )])){                               
                 $content = $pros[substr ( $keyword, 2 )];
                 $result = $this->transmitImage( $object, $content );   
                 }else{
                 $content = "这个省份没有哦";
                 $result = $this->transmitText ( $object, $content );     
                 }
                break;
                default:
                 $content = "收到消息啦";
                 $result = $this->transmitText ( $object, $content ); 
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
	private function transmitImage($object,$mediaid) { // irU924xhACd43a5fnQRfKbJ29eJGpHZAzLWfYfxZdWSoKtKSWxVzbOv3yTReDG3X
		$itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";
		
		$item_str = sprintf ( $itemTpl, $mediaid );
		
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