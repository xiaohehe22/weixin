<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/12
 * Time: 14:49
 */

//header("Content-Type:text/html;charset=utf-8");
header("Content-type: image/jpeg");
include_once ('functions.php');

define("TOKEN", "weixin");
define("appid","wxb19fa004abdf4374");
define("appsecret","82be22c5f63ac37bd59b528fe7482f9e");
define("access_token",get_access_token(appid,appsecret));
$access_token=access_token;
$openid="oFOw6w0hKMUFJ9X1OxTOe_zi2v2E";


$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
$qrcode='{"action_name": "QR_LIMIT_STR_SCENE","action_info": {"scene": {"scene_str": "'.$openid.'"}}}';
$output = https_request($url,$qrcode);

$json=json_decode($output,true);

$ticket=$json['ticket'];

$qrcodeUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);


/*$imageInfo=downloadImageFromWeixin($qrcodeUrl);
$filename="qrcode.jpg";
$local_file=fopen($filename,'w');
if (false!==$local_file){
    if (false!==fwrite($local_file,$imageInfo["body"])){
        fclose($local_file);
    }
}*/
$src_im = @imagecreatefromjpeg("qrcodeUrl");
$dst_im = @imagecreatefromjpeg("bg4.jpg");
//imagecopy( $dst_im, $src_im, 0, 0, 0, 0, 150, 150 );
imagecopyresampled($dst_im, $src_im, 250, 400, 0, 0, 150, 150, 430, 430);
$imagename='test.jpg';
imagejpeg($dst_im);
imagedestroy($dst_im);
imagedestroy($src_im);