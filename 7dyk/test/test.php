<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/12
 * Time: 14:49
 */

header("Content-Type:text/html;charset=utf-8");
include_once ('functions.php');

define("TOKEN", "weixin");
define("appid","wxb19fa004abdf4374");
define("appsecret","82be22c5f63ac37bd59b528fe7482f9e");
define("access_token",get_access_token(appid,appsecret));
$fromUsername="oFOw6w0hKMUFJ9X1OxTOe_zi2v2E";


$media_id=qrcode(access_token,$fromUsername);
send_image(access_token,$fromUsername,$media_id);