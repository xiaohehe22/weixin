<?php
$app = 'wx5584cfe083525d25';
$secret = '46d7cfe68853fe6950c86cbdd6aefd2f ';


$access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$app."&secret=".$secret;

$result =  file_get_contents($access_token);

$this_token =json_decode($json,true)["access_token"];

//UO98fIxsEVgjnog4ZsxgT7WZexm2MWeUctZkU3AoO3SMYffiwKM2vRvcBk2Ub2jS8HtIyF4HTO9O329H_yWAUwBkWI-6CtgdLEgKvY3zB9D11cK6dMglaraJrer-pYvcBWLaADAFAP


$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this_token;


$data = ' {

     "button":[
     {
           "name":"书单资料",
           "sub_button":[
           {	
               "type":"click",
               "name":"过来人推荐",
               "key":"menu1";
            },
            {
               "type":"click",
               "name":"入门宝典",
               "key":"menu2";
            },
       }
       
       ]
 }';


$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $MENU_URL); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

$info = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Errno'.curl_error($ch);
}

curl_close($ch);

var_dump($info);



?>