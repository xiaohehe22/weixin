<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/12
 * Time: 16:06
 */

$openid="oFOw6w0hKMUFJ9X1OxTOe_zi2v2E";
define("access_token","TYGN3F7P6i_7dtqdxNRRGZ5EZAe1Ynk845ecP6841rSv1OcRTDhP_IlnaDX4606ruGBIM9SWgsBgOjQcTJ1yOMQZT1ZPDNfI8yv9NdNfe8pdWdYrQnPvUqJ9hbR21hm8JFJgAEAWSW");
$access_token=access_token;
$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";


$qrcode='{"action_name": "QR_LIMIT_SCENE","action_info": {"scene": {"openid": '.$openid.'}}}';
$output = https_request($url,$qrcode);

$json=json_decode($output,true);

$ticket=$json['ticket'];

$qrcodeUrl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);


$imageInfo=downloadImageFromWeixin($qrcodeUrl);
$filename="qrcode.jpg";
$local_file=fopen($filename,'w');
if (false!==$local_file){
    if (false!==fwrite($local_file,$imageInfo["body"])){
        fclose($local_file);
    }
}
//header("Content-type: image/jpeg");
$src_im = @imagecreatefromjpeg("qrcode.jpg");
$dst_im = @imagecreatefromjpeg("bg4.jpg");

//imagecopy( $dst_im, $src_im, 0, 0, 0, 0, 150, 150 );
imagecopyresampled($dst_im, $src_im, 250, 400, 0, 0, 150, 150, 430, 430);
$imagename='test.jpg';
imagejpeg($dst_im,$imagename);

imagedestroy($dst_im);
imagedestroy($src_im);
/*
$filepath=dirname(__FILE__).'/'.$filename;
echo $filepath;
if (class_exists ( '\CURLFile' )) {//关键是判断curlfile,官网推荐php5.5或更高的版本使用curlfile来实例文件
    $filedata = array (
        'media' => new \CURLFile ( realpath ( $filepath ), 'image/jpeg' )
    );
} else {
    $filedata = array (
        'media' => '@'.$filepath
    );
}

print_r($filedata);
$urlMediaId="https://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=image";
$result=https_request($urlMediaId,$filedata);
$json=json_decode($result,true);

//$id=$json['media_id'];
echo $json['media_id'];
*/
//echo

function https_request($url, $data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function downloadImageFromWeixin($url){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_NOBODY, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    $httpinfo=curl_getinfo($curl);
    curl_close($curl);
    return array_merge(array('body'=>$output,array('header'=>$httpinfo)));
}





?>


