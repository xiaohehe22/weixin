<?php
include_once ('functions.php');
define("TOKEN", "weixin");
define("appid","wxb19fa004abdf4374");
define("appsecret","82be22c5f63ac37bd59b528fe7482f9e");
define("access_token",get_access_token(appid,appsecret));


$access_token= access_token;
$data = ' {

     "button":[
     {
           "name":"书单资料",
           "sub_button":[
           {	
               "type":"click",
               "name":"过来人推荐",
               "key":"menu1"
            },
            {
               "type":"click",
               "name":"入门宝典",
               "key":"menu2"
            }]
       },
       {
           "type":"click",
           "name":"讲座直播", 
           "key":"menu3"
       },
       {
           "type":"view",
           "name":"关于我们", 
           "url":"http://mp.weixin.qq.com/s?__biz=MzI2NTMxMTY0Nw==&mid=100000185&idx=2&sn=5b78477841cadcfe0173a94b6b77e6b7&scene=18#wechat_redirect"
       }
       ]
 }';


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $data);
var_dump($result);

?>