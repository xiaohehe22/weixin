<?php
include_once ('functions.php');
define("TOKEN", "weixin");
define("appid","wxfbe89421611bdb2d");
define("appsecret","cd9c70575b4895cf7e810dce18bd47bd");

define("access_token",get_access_token(appid,appsecret));
$access_token=access_token;


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
           "type":"click",
           "name":"关于我们", 
           "key":"menu4"
       }
       ]
 }';

//$url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url,$data);
var_dump($result);

?>