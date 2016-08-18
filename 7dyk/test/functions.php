<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/4
 * Time: 14:45
 */

function db_connect(){
    @$result = new mysqli('101.200.85.218', 'root','7DYKdev123!@#','test');
    if (!$result){
        echo 'Could not connect to database server';
        return false;
    }else{

        return $result;
    }
}

function filled_out($form_vars){
    foreach ($form_vars as $key=>$value){
        if ((!isset($key))||($value=='')){
            return false;
        }
    }
    return true;
}



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

function nickname($access_token,$openid){
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
    $output = https_request($url);
    $json=json_decode($output,true);
    $nickname = $json['nickname'];
    return $nickname;
}


function qrcode($access_token,$openid){
    if(isexistid($openid)){
        $mysql = db_connect();
        $nickname=nickname($access_token,$openid);
        $query="insert into user(nickname,openid) VALUES('$nickname','$openid')";
        $result = $mysql->query($query);
    }

    $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
    $qrcode='{"action_name": "QR_LIMIT_STR_SCENE","action_info": {"scene": {"scene_str": "'.$openid.'"}}}';
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
    $src_im = @imagecreatefromjpeg("qrcode.jpg");
    $dst_im = @imagecreatefromjpeg("bg4.jpg");
//imagecopy( $dst_im, $src_im, 0, 0, 0, 0, 150, 150 );
    imagecopyresampled($dst_im, $src_im, 250, 400, 0, 0, 150, 150, 430, 430);
    $imagename='test.jpg';
    imagejpeg($dst_im,$imagename);
    imagedestroy($dst_im);
    imagedestroy($src_im);
    $filepath=dirname(__FILE__).'/'.$imagename;
    if (class_exists ( '\CURLFile' )) {//关键是判断curlfile,官网推荐php5.5或更高的版本使用curlfile来实例文件
        $filedata = array (
            'media' => new \CURLFile ( realpath ( $filepath ), 'image/jpeg' )
        );
    } else {
        $filedata = array (
            'media' => '@'.$filepath
        );
    }

    $urlMediaId="https://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=image";
    $result=https_request($urlMediaId,$filedata);
    $json=json_decode($result,true);
    return $json['media_id'];
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


function vote($openid,$sourceId,$access_token){

    $mysql = db_connect();
    $nickname=nickname($access_token,$openid);
    //$query="insert into user(nickname,openid) VALUES('$nickname','$openid')";
    //$result = $mysql->query($query);
    $sql = "update user set vote = vote+1 where sourceid='$sourceId'";
    $mysql->query($sql);
    $sql = "select vote from user where sourceid='$sourceId'";
    $result = $mysql->query($sql);
    $test=$result->fetch_array();
    $msgType = "text";
    $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
    if($test[0]==5){
        $contentStr=$test[0];
    }else{
        $contentStr=$nickname.$test[0];
    }

    return $contentStr;
    //$result=sprintf($textTpl, $sourceId, $fromUsername, time(), $msgType, $contentStr);
    //echo $result;
}



/*
 * 是否存在该openid，存在返回ture，不存在false
 */
function isexistid($openId){
    $mysql = db_connect();
    $query="select openid from user where openid='$openId'";
    $result = $mysql->query($query);
    mysqli_close($mysql);
    if(!$result){
        return  false;
    }else{
        $test=$result->fetch_array();
        if (count($test)!=0){
            return true;
        }else{
            return false;
        }
    }
}

function isexist_sourceid($openId){
    $mysql = db_connect();
    $query="select openid from user where sourceid='$openId'";
    $result = $mysql->query($query);
    mysqli_close($mysql);
    if(!$result){
        return  false;
    }else{
        $test=$result->fetch_array();
        if (count($test)!=0){
            return true;
        }else{
            return false;
        }
    }
}


function send_template($access_token,$openid){
    $template = array('touser'=>"$openid",
        'template_id'=>"cqqzGqDiNs-e2fHAW59I9WIDFU_8USnYwQ-R_aXvTmU",
        'topcolor'=>"#7b68EE",
        'data'=>array()
    );
    $data=urldecode(json_encode($template));
    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
    $res=https_request($url,$data);
    var_dump(json_decode($res,true));
}

function send_image($access_token,$openid,$media_id){
    $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token";
    $img =  array('touser'=>"$openid",
        'msgtype'=>"image",
        'image'=>array(
            'media_id'=>"$media_id"
        )
    );
    $data=json_encode($img);
    $result=https_request($url,$data);
    return $result;
}


function send_text($access_token,$openid,$content){
    $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token";
    $text =  array('touser'=>"$openid",
        'msgtype'=>"text",
        'text'=>array(
            'content'=>urlencode($content)
        )
    );
    $data=urldecode(json_encode($text));
    $result=https_request($url,$data);
    return $result;
}


function get_access_token($appid,$appsecret){
    $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    $res=https_request($url);
    $result=json_decode($res,true);
    return $result["access_token"];
}


function get_image($access_token){
    $filepath=dirname(__FILE__).'/bg4.jpg';
    if (class_exists ( '\CURLFile' )) {//关键是判断curlfile,官网推荐php5.5或更高的版本使用curlfile来实例文件
        $filedata = array (
            'media' => new \CURLFile ( realpath ( $filepath ), 'image/jpeg' )
        );
    } else {
        $filedata = array (
            'media' => '@'.$filepath
        );
    }

    $urlMediaId="https://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=image";
    $result=https_request($urlMediaId,$filedata);
    $json=json_decode($result,true);
    return $json['media_id'];
}
