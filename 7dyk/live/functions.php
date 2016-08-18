<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/4
 * Time: 14:45
 */

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


//获取access_token
function get_access_token($appid,$appsecret){
    $conn=db_connect();
    $query="select * from access";

    $result=$conn->query($query);

    $access=$result->fetch_object();
    $access_time=$access->access_time;

    $time=time();

    if (($time-$access_time)>7000){
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $res=https_request($url);
        $result=json_decode($res,true);
        $access_token=$result["access_token"];
        $query="update access set access_time = '$time' , access_token = '$access_token'";
        $result=$conn->query($query);
        return $access_token;
    }else{
        return $access->access_token;
    }
}


//连接数据库
function db_connect(){
    @$result = new mysqli('101.200.85.218', 'root','7DYKdev123!@#','test');
    if (!$result){
        echo 'Could not connect to database server';
        return false;
    }else{

        return $result;
    }
}


//获取用户nickname
function nickname($access_token,$openid){
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
    $output = https_request($url);
    $json=json_decode($output,true);
    $nickname = $json['nickname'];
    return $nickname;
}

//发送模板消息
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



//客服回复发送文字
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


//客服回复发送海报图片
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


//获取海报图片的media_id
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

function save_register($openid){
    $mysql = db_connect();
    $query="select id from live where register = '$openid'";
    $result = $mysql->query($query);
    if(!$result){
        return  '发生某种错误了，请稍后再试';
    }else{
        $test=$result->fetch_array();
        if (count($test)!=0){
            return "你已经报名成功啦，视频直播会在8月18日周四晚上8点准时开始，我们会提前 10分钟在此服务号提醒你参加，请密切关注哦~如果你想要获得导师独家的PPT ，请回复【ppt】\n欢迎大家在后台向导师提问，我们会尽全力帮助你解决问题哒\n创业小伙伴，发送BP到market@7dyk.com，直接与导师交流！";
        }else{
            $query="insert into live(register) VALUES('$openid')";
            $mysql->query($query);
            return "恭喜你报名成功啦！视频直播会在8月18日周四晚上8点准时开始，我们会提前 10分钟在此服务号提醒你参加，请密切关注哦~如果你想要获得导师独家的PPT ，请回复【ppt】\n欢迎大家在后台向导师提问，我们会尽全力帮助你解决问题哒\n创业小伙伴，发送BP到market@7dyk.com，直接与导师交流！";
        }
    }
}

function save_ppt($openid){
    $mysql = db_connect();
    $query="select id from live where register = '$openid'";
    $result = $mysql->query($query);
    if(!$result){
        return  '发生某种错误了，请稍后再试';
    }else{
        $test=$result->fetch_array();
        if (count($test)==0){
            return "你还未报名，请回复【001】进行报名";
        }else{
            $query="update live set ppt=1 where id = '$test[0]'";
            $mysql->query($query);
            return  "请分享下面的海报到朋友圈，并配文字“已报名”，截图发送到后台就可以啦";
        }
    }
}

function save_image($openid){
    $mysql = db_connect();
    $query="select register from live where register = '$openid' and ppt = 1";
    $result = $mysql->query($query);
    if(!$result){
        return  '发生某种错误了，请稍后再试';
    }else{
        $test=$result->fetch_array();
        if (count($test)==0){
            return "你还未报名，请回复【001】进行报名,按提示进行";
        }else{
            $query="update live set ppt=1 where register = '$test[0]'";
            $mysql->query($query);
            return  "小师兄收到啦，直播结束后PPT就会飞到你手中了";
        }
    }
}


function response_news($object,$newsContent){
    $newsTplHead = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>
                <ArticleCount>%s</ArticleCount>
                <Articles>";
    $newsTplBody = "<item>
                <Title><![CDATA[%s]]></Title> 
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";
    $newsTplFoot = "</Articles>
                <FuncFlag>0</FuncFlag>
                </xml>";
    $num=count($newsContent);
    $header = sprintf($newsTplHead, $object->FromUserName, $object->ToUserName, time(),$num);
    $body="";
    foreach($newsContent as $key => $value){
        $body .= sprintf($newsTplBody, $value['title'], $value['description'], $value['picUrl'], $value['url']);
    }

    $FuncFlag = 0;
    $footer = sprintf($newsTplFoot, $FuncFlag);
    return $header.$body.$footer;
}




