<?php
/**
  * wechat php test
  */

 //define your token
        define("TOKEN", "weixin");
        $wechatObj = new wechatCallbackapiTest();
        //验证服务器和公众平台是否连接成功
        //在服务器和公众平台验证成功之后，把$this->valid()注释掉
        // $wechatObj->valid();
        set_time_limit(30);
        echo $wechatObj->responseMsg();

class wechatCallbackapiTest
{
    // public function index()
    // {
    //     //define your token
    //     define("TOKEN", "weixin");
    //     // $wechatObj = new wechatCallbackapiTest();
    //     //验证服务器和公众平台是否连接成功
    //     //在服务器和公众平台验证成功之后，把$this->valid()注释掉
    //     // $this->valid();
    //     set_time_limit(30);
    //     echo $this->responseMsg();
    // }
    /**
     * 验证服务器
     * @return [type] [description]
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
     * 获取access_token
     */
    function access_token(){
        $appid = 'wxfbe89421611bdb2d';
        $appsecret = 'cd9c70575b4895cf7e810dce18bd47bd';
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $file = $this->curlPost($url,'','GET');
        $arr = json_decode($file,'true');

        return $arr['access_token'];
    }

    //输出公众平台返回给用户的信息
    public function responseMsg()
    {
		//get post data, May be due to the different environments
        //相当于$_POST
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                //只解析XML数据的主体部分，防止xxe攻击
                libxml_disable_entity_loader(true);
                //解析XML数据
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);              
                //发送文本信息的关键字
                // $keyword = trim($postObj->Content);
                //发送消息的类型
                $RX_TYPE = trim($postObj->MsgType);
                // $time = time();
                //消息类型分离
                switch ($RX_TYPE)
                {
                    case "event":
                        $result = $this->receiveEvent($postObj);
                        break;
                    case "text":
                        $result = $this->receiveText($postObj);
                        break;
                    case "image":
                        $result = $this->transmitText($postObj,'已收到您的图片信息，谢谢！');
                        break;
                    case "voice":
                        $result = $this->transmitText($postObj,'已收到您的语音信息，谢谢！');
                        break;
                    case "video":
                        $result = $this->transmitText($postObj,'已收到您的视频信息，谢谢！');
                        break;
                    case "shortvideo":
                        $result = $this->transmitText($postObj,'已收到您的小视频信息，谢谢！');
                        break;
                    case "location":
                        $content = "上传位置：纬度 ".$postObj->Location_X.";经度 ".$postObj->Location_Y.";缩放度 ".$postObj->Scale.';位置信息:'.$postObj->Label;
                        $result = $this->transmitText($postObj,$content);
                        break;
                    case "link":
                        $result = $this->transmitText($postObj,'已收到您的链接信息，谢谢！');
                        break;
                    default:
                        $result = $this->transmitText($postObj,'已收到您的'.$RX_TYPE.'信息，谢谢！');
                        break;
                }
                //$this->logger("T ".$result);
                echo $result;
            }else {
            	echo "客官，我书读的少，不知道你想要什么服务，回复关键字有惊喜：'音乐'，'单图文'，'多图文'";
            	exit;
        }
    }

    /**
     * 事件推送
     * @param  [type] $object [description]
     * @return [type]         [description]
     */
    function receiveEvent($object)
    {
        // $path=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].Yii::$app->request->baseUrl.'/../upload/';
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
                
                $pdo = new PDO('mysql:dbname=wechat;host=localhost','root','7DYKdev123!@#');
                $pdo->query('set names utf8');
                // 获取access_token
                $access_token = $this->access_token();
                //获取用户openid
                $openid = $object->FromUserName;
                //将关注用户信息入库
                $sql = "insert into weixin_user(wx_id,openid) values(null,'$openid')";
                $pdo->exec($sql);
                $wx_id = $pdo->lastInsertId(); //取得新插入ID
                // $sql1  = 'select wx_id from weixin_user where openid='.$openid;  //openid唯一，通过openid获得用户id
                // $wx_id = $pdo->query($sql1)->fetch(PDO::FETCH_ASSOC)['wx_id'];
                $url="http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=image";
                // $path='./upload/';
                // $path=str_replace('\\','/',$path);
                if (class_exists('\CURLFile')) {
                    $arr['media']=new \CURLFile($this->get_ticket($openid,$wx_id));
                } else {
                    $arr['media']='@'.$this->get_ticket($openid,$wx_id);
                }
                $res=$this->curlPost($url,$arr,'POST');
                $res=json_decode($res);
                $media_id=$res->media_id;
                // $openid=$ins['p_username'];
                $url1="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
                $text='{"touser":"'.$openid.'","msgtype":"text","text":{"content":"欢迎来到7点一刻"}}';
                $image='{"touser":"'.$openid.'","msgtype":"image","image":{"media_id":"'.$media_id.'"}}';
                $text1='{"touser":"'.$openid.'","msgtype":"text","text":{"content":"请保存上面的二维码，转发给你的小伙伴儿们，成功邀请5人，会有惊喜呦！！"}}';
                $this->curlPost($url1,$text,'POST');
                $this->curlPost($url1,$image,'POST');
                $this->curlPost($url1,$text1,'POST');
                if(isset($object->EventKey) && !empty($object->EventKey)){
                    $ekey=str_replace("qrscene_","",$object->EventKey);
                    $sql="update weixin_user set wx_fen=wx_fen+1 where wx_id=".$ekey;
                    
                    $pdo->exec($sql); //使粉丝加1
                    $sql1="select wx_fen,openid from weixin_user where wx_id=".$ekey;
                    
                    $wx_fen = $pdo->query($sql1)->fetch(PDO::FETCH_ASSOC)['wx_fen'];

                    $wx_openid = $pdo->query($sql1)->fetch(PDO::FETCH_ASSOC)['openid'];
                    $text2='{"touser":"'.$wx_openid.'","msgtype":"text","text":{"content":"又有小伙伴祝你一臂之力了，人气值+1，总人气：'.$wx_fen.'"}}';
                    
                    $this->curlPost($url1,$text2,'POST');
                    if($wx_fen == 5){
                        //传送门
                        $go = "http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000083&idx=1&sn=d560e3b48d3896dd8077aac4937d8c04&scene=20&from=singlemessage&isappinstalled=0#wechat_redirect";
                        $text3= '{"touser":"'.$wx_openid.'","msgtype":"text","text":{"content":"哇哦！！您已经有5位粉丝了哦<br/>'.$go.'"}}';  
                        $this->curlPost($url1,$text3,'POST');
                    }
                }
                
                break;
            case "unsubscribe":
                $content = "取消关注";
                break;
            case "SCAN":
                $content = "您辣么喜欢这个活动呀，快快邀请您的小伙伴儿们来吧，成功邀请5人有惊喜呦！";//"扫描场景 ".$object->EventKey;
                break;
            case "CLICK":
                $content = "点击菜单：".$object->EventKey;
                break;
            case "LOCATION":
                $content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude.";精确度 ".$object->Precision;
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "MASSSENDJOBFINISH":
                $content = "消息ID：".$object->MsgID."，结果：".$object->Status."，粉丝数：".$object->TotalCount."，过滤：".$object->FilterCount."，发送成功：".$object->SentCount."，发送失败：".$object->ErrorCount;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }
        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }

    /**
     * 获取带参数二维码 【临时二维码】
     * @param  [type] $openid [description] 用户openid
     * @return [type] $wx_id  [description] 用户id
     */
    function get_ticket($openid,$wx_id){
        
        $access_token=$this->access_token();
        //return $arr2['p_id'];
        $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        
        $qrcode='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$wx_id.'}}}';
        $result=$this->curlPost($url,$qrcode,'POST');
        $result=json_decode($result,'true');
        $ticket=$result['ticket'];
        // $ticket=urlencode($ticket);
        $url1 ='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
        $result1 = $this->curlPost($url1,'','GET');
        
        // $arr = json_decode($result1,true);
        $path='./upload/';
        $path=str_replace('\\','/',$path);
        file_put_contents($path.'qrcode.jpg',$result1);
        //按比例缩放二维码图片
        $new_qrcode = $this->imgCreate($path.'qrcode.jpg');
        // return $arr['code_id'];
        
        //获取该关注用户信息
        $url2 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo = $this->curlPost($url2,'','GET');
        $userinfo1 = json_decode($userinfo);
        //用户头像
        $user_img = $userinfo1->headimgurl;
        //用户昵称
        // $arr['$user_nick'] = $userinfo1->nickname;
        // return $user_img;
        $user_img = substr($user_img,0,-1);
        // // $user_img = str_replace('0','\\46',$user_img);
        $user_img = $this->curlPost($user_img.'96','','GET');
        // // return $user_img;
        file_put_contents($path.'user_headimg.jpg',$user_img);
        
        // return $user_nick;
        //背景图片为
        $target   = $path.'/beijing.jpg';
        $user_img = './upload/user_headimg.jpg';
        $ticket   = $new_qrcode;
        $img = $this->imgCopy($target,$user_img,$ticket);
        
        return  $img;
    }

    /**
     * 按比例缩放二维码图片
     */
    function imgCreate($src){
        // 指定文件路径和缩放比例(1/3)
        $filename = $src;
        $percent = 1/3;
        // 指定头文件Content typezhi值
        // header('Content-type: image/jpeg');
        // 获取图片的宽高
        list($width, $height) = getimagesize($filename);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        // 创建一个图片。接收参数分别为宽高，返回生成的资源句柄
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        //获取源文件资源句柄。接收参数为图片路径，返回句柄
        $source = imagecreatefromjpeg($filename);
        // 将源文件剪切全部域并缩小放到目标图片上。前两个为资源句柄
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // 保存到指定文件
        // $path = './upload/';
        // $newFilename = 'qrcode.jpg';
        imagejpeg($thumb,'./upload/new_qrcode.jpg');

        return './upload/new_qrcode.jpg';
    }
    /**
     * 生成最终要返回的图片
     * @param  [type] $target   [description] 背景图片
     * @param  [type] $user_img [description] 用户头像
     * @param  [type] $ticket   [description] 二维码图片
     * @return [type]           [description]
     */
    function imgCopy($target,$user_img,$ticket){
        $imgs = array(); 

        $imgs[0] = $user_img; 
        $imgs[1] = $ticket; 
        // $target = 'backimg.jpg';//背景图片 
        $target_img = Imagecreatefromjpeg($target);
        $source= array(); 
        foreach ($imgs as $k=>$v){ 
            $source[$k]['source'] = Imagecreatefromjpeg($v);
            $source[$k]['size'] = getimagesize($v);
            
        } 
        $num=0; //控制列数，一行几列，0为1以此类推。 
        $tmp=25; //第一张图片距离左侧距离
        $tmpy=450; //图片之间的间距（第一张图片距离顶部距离）
        // 组合
        for ($i=0; $i<4; $i++){  
            imagecopy($target_img,$source[$i]['source'],$tmp,$tmpy,0,0,$source[$i]['size'][0],$source[$i]['size'][1]); 
            $tmp = $tmp+$source[$i]['size'][0]; 
            $tmp = $tmp+5; // 
            if($i==$num){ 
                $tmpy = $tmpy+$source[$i]['size'][1]; 
                $tmpy = $tmpy+100; //背景图上的两张图片的间距
                $tmp=11; //最下边（第二张）图片距离左侧距离
                $num=$num+3; 
            } 
        } 
        //保存最终图片到文件
        Imagejpeg($target_img,'./upload/pin.jpg');

        return './upload/pin.jpg';
    }

    /**
     * 文本类消息回复
     * @param  [type] $object [description]
     * @return [type]         [description]
     */
    private function receiveText($object)
    {
        // $path=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].Yii::$app->request->baseUrl.'/../upload/';
        // $keyword = trim($object->Content);
        // $arr=$this->sel_eve($object->ToUserName,$keyword);
        // if($arr==0){
        //     $content=$this->content;
        // }
        // else{
        //     if($arr['r_type']==1){
        //         $content=$arr['r_content'];
        //     }elseif($arr['r_type']==2){
        //         $content=array();
        //         foreach($arr['child'] as $vn){
        //             $content[] = array("Title"=>$vn['i_title'],  "Description"=>$vn['i_content'], "PicUrl"=>$path.$vn['i_image'], "Url" =>$vn['i_url']);
        //         }
        //     }elseif($arr['r_type']==3){
        //         $music=$arr['child'][0];
        //         $content =array("Title"=>$music['i_title'], "Description"=>$music['i_content'], "MusicUrl"=>$path.$music['i_image'], "HQMusicUrl"=>$path.$music['i_image']);
        //     }else{
        //         $content = date("Y-m-d H:i:s",time())."\n".$object->FromUserName."\n技术支持 OneTeam微信开发团队";
        //     }
        // }
        // if(is_array($content)){
        //     if (isset($content[0]['PicUrl'])){
        //         $result = $this->transmitNews($object, $content);
        //     }else if (isset($content['MusicUrl'])){
        //         $result = $this->transmitMusic($object, $content);
        //     }
        // }else{
        //     $result = $this->transmitText($object, $content);
        // }
        // return $result;
        $funcFlag = 0;
        $contentStr = "你发送的是文本，内容为：".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        return $resultStr;
    }

    //回复文本消息
    private function transmitText($object, $content)
    {
        $xmlTpl =  "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl =  "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    $item_str
                    </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复图文消息
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
                        <Title><![CDATA[%s]]></Title>
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl =  "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%s</ArticleCount>
                    <Articles>
                    $item_str</Articles>
                    </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <MusicUrl><![CDATA[%s]]></MusicUrl>
                    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                    </Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl =  "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[music]]></MsgType>
                    $item_str
                    </xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复多客服消息
    private function transmitService($object)
    {
        $xmlTpl =  "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                    </xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    /**
     * curl方法
     * @param  [type] $url    [description]
     * @param  [type] $data   [description]
     * @param  [type] $method [description]
     * @return [type]         [description]
     */
    function curlPost($url,$data,$method){
        $ch = curl_init();   //1.初始化
        curl_setopt($ch, CURLOPT_URL, $url); //2.请求地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);//3.请求方式
        //4.参数如下
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);//https
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');//模拟浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept-Encoding: gzip, deflate'));//gzip解压内容
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        if($method=="POST"){//5.post方式的时候添加数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);//6.执行

        if (curl_errno($ch)) {//7.如果出错
            return curl_error($ch);
        }
        curl_close($ch);//8.关闭
        return $tmpInfo;
    }

	//验证TOKEN	
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
            header('content-type:text');
			return true;
		}else{
			return false;
		}
	}
}

?>