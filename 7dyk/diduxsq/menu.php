<?php
$app = 'wxef131d66470bd9c2';
$secret = '81743069477d9383e6591019ee1881ba';


$access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$app."&secret=".$secret;

$result =  file_get_contents($access_token);

$this_token =json_decode($json,true)["access_token"];

//UO98fIxsEVgjnog4ZsxgT7WZexm2MWeUctZkU3AoO3SMYffiwKM2vRvcBk2Ub2jS8HtIyF4HTO9O329H_yWAUwBkWI-6CtgdLEgKvY3zB9D11cK6dMglaraJrer-pYvcBWLaADAFAP


$MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this_token;


$data = '{
 "button":[           
  {
       "name":"分支主菜单名",　　
       "sub_button":[　　　　
        {
           "type":"click",　　
           "name":"分支子菜单名1",　　
           "key":"loveSuzhou"　　
        },
        {
           "type":"click",
           "name":"分支子菜单名2",
           "key":"liveSuzhou"
        }]
   },　　　　
   {
       "type":"click",
       "name":"7：15Fm",
       "key":"lianxiUs"
   }]
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