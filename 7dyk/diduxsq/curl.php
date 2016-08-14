<?php  
  

$pros=array("8");

for($i=0;$i<count($pros);$i++){
  getid($pros[$i]);
}

//get_user("oHq4NwP51JZy_7Lrevg3h6cxTW1E");

   
    $appid = "wxef131d66470bd9c2";  
    $appsecret = "81743069477d9383e6591019ee1881ba";  
   

function getid($name){
    $appid = "wxef131d66470bd9c2";  
    $appsecret = "81743069477d9383e6591019ee1881ba";  
   
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";  
    
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    $output = curl_exec($ch);  
    curl_close($ch);  
    $jsoninfo = json_decode($output, true);  
    $access_token = $jsoninfo["access_token"];  
//获取access_token
   $file_info=array(
    'filename'=>$name.'.jpg',  //国片相对于网站根目录的路径
    'content-type'=>'image/jpeg',  //文件类型

);   
echo $name.":";
echo add_material($access_token,$file_info)."\n"; 
}





function get_users($url) {
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";  
   
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    $output = curl_exec($ch);  
    curl_close($ch);  
    $jsoninfo = json_decode($output, true);  
    $access_token = $jsoninfo["access_token"];    
    
    $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";  
    
    $result = https_request($url); 
    $jsoninfo = json_decode($result);  // 默认false，为Object，若是True，为Array  
       
    $data = $jsoninfo -> data;    
       
    foreach($data -> openid as $x=>$x_value) {  
        echo $x_value . ",";  
        echo "<br>";  
    }  

}
   function get_user($openid) {
    
    $appid = "wx7d08599a2b86ac00";  
    $appsecret = "c868a69fefe6118503cdb3900133484e";  
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";  
   
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    $output = curl_exec($ch);  
    curl_close($ch);  
       
    $jsoninfo = json_decode($output, true);  
    $access_token = $jsoninfo["access_token"];  
    
    $url = " https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid";
        
    $result = https_request($url); 
       
       		   $jsoninfo2 = json_decode(  $result, true );
       
              /*
       {
    "subscribe": 1,
    "openid": "oLVPpjqs2BhvzwPj5A-vTYAX4GLc",
    "nickname": "刺猬宝宝",
    "sex": 1,
    "language": "zh_CN",
    "city": "深圳",
    "province": "广东",
    "country": "中国",
    "headimgurl": "http://wx.qlogo.cn/mmopen/JcDicrZBlREhnNXZRudod9PmibRkIs5K2f1tUQ7lFjC63pYHaXGxNDgMzjGDEuvzYZbFOqtUXaxSdoZG6iane5ko9H30krIbzGv/0",
    "subscribe_time": 1386160805
}
       */
				
				$nickname = $jsoninfo2 ["nickname"]?$jsoninfo2 ["nickname"]:"nonickname";
				$sex = $jsoninfo2 ["sex"]?$jsoninfo2 ["sex"]:0;
				$province = $jsoninfo2 ["province"]?$jsoninfo2 ["province"]:"noprovince";
				$city = $jsoninfo2 ["city"]?$jsoninfo2 ["city"]:"nocity";
				$country = $jsoninfo2 ["country"]?$jsoninfo2 ["country"]:"nocountry";
				$headimgurl = $jsoninfo2 ["headimgurl"]?$jsoninfo2 ["headimgurl"]:"noheadimgurl";
       
       echo $headimgurl;      

}

    function https_request($url)  
    {         
        $curl = curl_init();         
        curl_setopt($curl, CURLOPT_URL, $url);         
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);         
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);         
        $data = curl_exec($curl);         
        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}         
        curl_close($curl);         
        return $data;  
    }        
   


  function add_material($access_token,$file_info){
      
  $url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type=image";
  $ch1 = curl_init ();
  $timeout = 5;
  $real_path="{$file_info['filename']}";
  //$real_path=str_replace("/", "\\", $real_path);
  $data= array("media"=>"@{$real_path}",'form-data'=>$file_info);
  curl_setopt ( $ch1, CURLOPT_URL, $url );
  curl_setopt ( $ch1, CURLOPT_POST, 1 );
  curl_setopt ( $ch1, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt ( $ch1, CURLOPT_CONNECTTIMEOUT, $timeout );
  curl_setopt ( $ch1, CURLOPT_SSL_VERIFYPEER, FALSE );
  curl_setopt ( $ch1, CURLOPT_SSL_VERIFYHOST, false );
  curl_setopt ( $ch1, CURLOPT_POSTFIELDS, $data );
  $result = curl_exec ( $ch1 );
  curl_close ( $ch1 );
      
      //  if(curl_errno()==0){
    $result=json_decode($result,true);
    //var_dump($result);
    return $result['media_id'];
      //}else {
      //return false;
      // }
}


?> 