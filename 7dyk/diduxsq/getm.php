<?php    

            
           $appid = "wx7d08599a2b86ac00";  
           $appsecret = "c868a69fefe6118503cdb3900133484e";  
           $weixin_access = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' .$appsecret;
			
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $weixin_access );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
			$output = curl_exec ( $ch );
			curl_close ( $ch );
			
			$jsoninfo = json_decode ( $output, true );
			$access_token = $jsoninfo ["access_token"];
			$openid = $jsoninfo ["openid"];
			$unionid = $jsoninfo ["unionid"];
			
			$errcode = $jsoninfo ["errcode"];
			if($errcode){
				return array("result"=>"failure");
			};
			
			
			$userinfo = M ( 'userinfo' )->where ( array (
					"openid" => $openid 
			) )->find ();
			
			if (!$userinfo) {
				$weixin_message = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid;
				$ch2 = curl_init ();
				curl_setopt ( $ch2, CURLOPT_URL, $weixin_message );
				curl_setopt ( $ch2, CURLOPT_SSL_VERIFYPEER, FALSE );
				curl_setopt ( $ch2, CURLOPT_SSL_VERIFYHOST, FALSE );
				curl_setopt ( $ch2, CURLOPT_RETURNTRANSFER, 1 );
				$output2 = curl_exec ( $ch2 );
				curl_close ( $ch2 );
				
				$jsoninfo2 = json_decode ( $output2, true );
				
				$nickname = $jsoninfo2 ["nickname"]?$jsoninfo2 ["nickname"]:"nonickname";
				$sex = $jsoninfo2 ["sex"]?$jsoninfo2 ["sex"]:0;
				$province = $jsoninfo2 ["province"]?$jsoninfo2 ["province"]:"noprovince";
				$city = $jsoninfo2 ["city"]?$jsoninfo2 ["city"]:"nocity";
				$country = $jsoninfo2 ["country"]?$jsoninfo2 ["country"]:"nocountry";
				$headimgurl = $jsoninfo2 ["headimgurl"]?$jsoninfo2 ["headimgurl"]:"noheadimgurl";
				
				$data ['openid'] = $openid;
				$data ['unionid'] = $unionid;
				$data ['nickname'] = $nickname;
				$data ['sex'] = $sex;
				$data ['province'] = $province;
				$data ['city'] = $city;
				$data ['country'] = $country;
				$data ['headimgurl'] = $headimgurl;
				$data ['createtime'] = date('Y-m-d H:i:s');
				
				M ( 'userinfo' )->add ( $data );
				
				$userinfo = array("openid"=>$openid,"nickname"=>$nickname);
			}

			
			return array("result"=>"success","openid"=>$userinfo["openid"],"nickname"=>$userinfo["nickname"]);

?>