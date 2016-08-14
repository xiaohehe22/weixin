<?php

$appid="wxa651fe7c815ec769";//填写appid
$secret="efba3a0b5d11f104a7f5cbf7f9c45863 ";//填写secret

$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$a = curl_exec($ch);


$strjson=json_decode($a);
$token = $strjson->access_token;
$post="{
	   \"button\":[
	    {
		   \"type\":\"view\",
           \"name\":\"节操呢？\",
           \"url\":\"http://s.p.qq.com/pub/jump?d=AAAObWk0\"
			
		},		
        {
			 \"name\":\"粉丝优惠\",
             \"sub_button\":[             
		    {
               \"type\":\"view\",
               \"name\":\"Q大道\",
               \"url\":\"http://wap.koudaitong.com/v2/goods/a4hmxwey\"
            },
            {
               \"type\":\"view\",
               \"name\":\"薰衣草撕名牌\",
               \"url\":\"http://wap.koudaitong.com/v2/goods/30e1rjqx\"
            },
            {
               \"type\":\"view\",
               \"name\":\"优惠券\",
               \"url\":\"http://m.5ikfc.com/\"
            }
       
			]
		},
		{
			 \"name\":\"小鲸鱼\",
             \"sub_button\":[             
		    {
               \"type\":\"view\",
               \"name\":\"历史消息\",
               \"url\":\"http://mp.weixin.qq.com/mp/getmasssendmsg?__biz=MzA3NTU2NTYzOA==#wechat_webview_type=1&wechat_redirect\"
            },
            {
               \"type\":\"view\",
               \"name\":\"联系我们\",
               \"url\":\"http://mp.weixin.qq.com/s?__biz=MzA3NTU2NTYzOA==&mid=201794337&idx=1&sn=e0f6abd1b983d50410be9730335f7df5#rd\"
            },
            {
               \"type\":\"view\",
               \"name\":\"我来参与\",
               \"url\":\"http://mp.weixin.qq.com/s?__biz=MzA3NTU2NTYzOA==&mid=201540811&idx=1&sn=f129e0ce91a2b83140566274f3d119d8#rd\"
            }
       
			]
		}]
	   
 }";  //提交内容
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$token}"; //查询地址 
$ch = curl_init();//新建curl
curl_setopt($ch, CURLOPT_URL, $url);//url  
curl_setopt($ch, CURLOPT_POST, 1);  //post
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);//post内容  
curl_exec($ch); //输出   
curl_close($ch); 

?>