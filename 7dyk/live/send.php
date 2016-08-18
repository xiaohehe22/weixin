<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/16
 * Time: 16:25
 */


header("Content-Type:text/html;charset=utf-8");
include_once ('functions.php');

define("TOKEN", "weixin");
define("appid","wxfbe89421611bdb2d");
define("appsecret","cd9c70575b4895cf7e810dce18bd47bd");
define("access_token",get_access_token(appid,appsecret));

$mysql = db_connect();
$query="select register from live";
$result=$mysql->query($query);


for($i=0;$openid=$result->fetch_object();$i++){
    send_text(access_token,$openid->register,'提醒测试');
}



