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
				$content = "毕业后的职场生活，比你想象中更有趣\n
回复【侦探】，和夏洛克一起破解真相\n
回复【考研】or【工作】，来做个测试，看看你适合考研还是工作\n
回复【研究生】，看一篇文章，你觉得《考研究生真的有用吗？》\n
回复【7】 领取惊天资料包\n
回复【产品经理】或【资料】，领取经验心得\n
回复【报名】，即可参与免费的班级旅行，回复【规则】，查看详细攻略";
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
				if (time () < strtotime ( '2016-04-08 24:00:00' )) {
					$content = array ();
					$content [] = array (
							"Title" => "来一场说走就走的免费旅行",
							"Description" => "来一场说走就走的免费旅行",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcEUgloTKmaN66JLEUicYPSUuKdowBXPZqZ0oPCl0IqIXjziaq5q4IxFsjGJuxibvgkoyribEhCs8SWMqQ/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=402465537&idx=1&sn=a92f171af88c4b934ae0989c7bf30c37#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
				} else {
					$content = "免费班级旅行活动已结束";
					$result = $this->transmitText ( $object, $content );
				}
				break;
			case "报名" :
				if (time () < strtotime ( '2016-04-08 24:00:00' )) {
                    $content = "欢迎报名：<a href=\"http://2.diduxsq.sinaapp.com/register.php\">戳这里</a>";
				} else {
					$content = "免费班级旅行活动已结束";
				}
				$result = $this->transmitText ( $object, $content );
				break;
			case "投票" :
            case "支持" :
				$action = new voteAction ();
            if (time () < strtotime ( '2016-04-08 24:00:00' )) {
					$content = $action->memberVote ( substr ( $keyword, 6 ), $object->FromUserName );
				} else {
					$content = "免费班级旅行活动已结束";
				}				
				$result = $this->transmitText ( $object, $content );
				break;
			case "查询" :
			case "查看" :
				$action = new voteAction();
				if (time () < strtotime ( '2016-04-08 24:00:00' )) {
					$content = $action->memberInquire ( substr ( $keyword, 6 ) );
				}  else {
					$content = "免费班级旅行活动已结束";
				}				
				$result = $this->transmitText ( $object, $content );
				break;
			default :
            switch($keyword){
            case "研究生" :
				$content = array ();
					$content [] = array (
							"Title" => "考研究生真的有用吗？",
							"Description" => "考研究生真的有用吗？",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcFhkfclwU5OnmebGm9ZuKu3RXGrvq16jNrOnyj4ZS8iaXibbFXJ57YZECiaBuY22UpyBC6U8Mar6eehA/0?wx_fmt=png",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=402191048&idx=1&sn=1bac5190a180d5658331cc6f1d3dde6e#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
            break;
            case "侦探" :
            case "推理" :
				$content = array ();
					$content [] = array (
							"Title" => "（内附解答）侦探推理故事合辑，回复对应数字查看",
							"Description" => "（内附解答）侦探推理故事合辑，回复对应数字查看",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcEBFc5TLPbuyNoy5aWSFLzjRjIw6Ps7orLKwvicnltZt97IiaOlSk6l4Yw5mVNIf4ib6gC9RvLx9JIVA/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=401344448&idx=1&sn=69bf316e9ea304940b9eb5242f15d2ec#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
            break;
            case "考研" :
            case "工作" :
				$content = array ();
					$content [] = array (
							"Title" => "心理测试：你适合考研还是工作",
							"Description" => "心理测试：你适合考研还是工作",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcFoEf69gic44EHwPiby9iaHRwhGsAHoQBGhO5y7X3RygyjldquDNtCdtfQI6NZGuP22pImm1ibPzRibsKw/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=402228638&idx=1&sn=9a612ac5577c1165ca6f60a0958c9a57#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
            break;  
            case "7" :
				$content = array ();
					$content [] = array (
							"Title" => "惊天资料包",
							"Description" => "惊天资料包",
							"PicUrl" => "https://mmbiz.qlogo.cn/mmbiz/mIw0gFAkDcEvv2L6FJaOwEVjWpztcxpIaK0ib3oauib1zVnL0JicqR4ZibBdLicM8lDkFKR9e8eUazXXibEqNmVFia84g/0?wx_fmt=jpeg",
							"Url" => "http://mp.weixin.qq.com/s?__biz=MzIwMzE0OTUyMg==&mid=402086313&idx=1&sn=bdf116f148e04f66646ee782c4552539#rd" 
					);
					$result = $this->transmitNews ( $object, $content );
            break;  
            case "产品经理" :
            case "资料" :
				 $content = "点击这里获取：<a href=\"http://www.ziyoufang.com/npp/player2?nppId=10548\">戳这里</a>";
                 $result = $this->transmitText ( $object, $content );
            break;
                case "沙龙":
				 $content = "1. 将上面海报分享至朋友圈，并附上【报名参加】字样\n2. 扫描二维码加小师兄为好友，将分享截图发送给小师兄\n3. 等待小师兄拉群\n4. 因为人数较多，所以请不要多次发送截图，请耐心等待\n5. 进群同学请按群公告填写报名表，小师兄将按报名信息发送邀请函，务必真实可靠";
                 $result = $this->transmit2( $object, $content );
            break;
            default :
                $content = "收到消息啦，我们会尽快回复~\n回复【规则】看看我们最近准备的活动吧。";
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
    
    
    //测试
    private function transmit2($postObj, $content) {
		$fromUsername = $postObj->FromUserName;
		$toUsername = $postObj->ToUserName;
        $time= time();
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
		$msgType = "text";
		$result1 = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType,$content );
        
        
        $mysql = new SaeMysql();        
        $sql = "select * from access order by time desc";
        $result2 = $mysql->getData($sql);
        
        if($result2!=null){
        	$access = $result2[0]["access"];
            $time = $result2[0]["time"]; 
            if(time() - $time <7000){
                $this->post_text($access,$fromUsername,'fe1hR-KoBEfN3No487i4hAom9h47g7HYNLsXM4dzpsQ');
                $this->post_text($access,$fromUsername,'fe1hR-KoBEfN3No487i4hPYBIpCuBUNSYcVnEQtnKhU');
               return $result1;            
            }
        } 
        
        $app ='wxef131d66470bd9c2';
        $secret ='81743069477d9383e6591019ee1881ba';        
		$access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$app."&secret=".$secret;
		$json =  file_get_contents($access_token_url);
        $this_token =json_decode($json,true);
        $this_token =$this_token["access_token"]; 
        
		$sql = "insert into access ('access','time') values ('$this_token','$time')";
		$mysql->runSql($sql);           
		$mysql->closeDb();
        $this->post_text($this_token,$fromUsername,'fe1hR-KoBEfN3No487i4hAom9h47g7HYNLsXM4dzpsQ');  
        $this->post_text($this_token,$fromUsername,'fe1hR-KoBEfN3No487i4hPYBIpCuBUNSYcVnEQtnKhU');   
        
        
        
        return $result1;
	}
    
    
private function post_text($this_token,$openid,$id){    
$URL="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this_token;
$data = '{
    "touser":"'.$openid.'",
      "msgtype":"image",
    "image":
    {
      "media_id":"'.$id.'"
    }
}';

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $URL); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

$info = curl_exec($ch);    
    
}
}

?>