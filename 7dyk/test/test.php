<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/12
 * Time: 14:49
 */
$openId="oFOw6w0hKMUFJ9X1OxTOe_zi2v2E";
define("access_token","XPKLmnOLxBGQlXG0VY6SUaiYh04akn9gANY5VCT89HPpPd4BcFo57GgXBd7NGVJa8qjcbPdR-EhBWTie-6epSDqZlIS5ZP0on9Cb-wvfctgDFMaAIAHJQ");

$mysql = db_connect();
$query="select openid from user where openid=$openId";
$result = $mysql->query($query);
mysqli_close($mysql);
if(!$result){
    echo 1;
}else{
    $test=$result->fetch_array();
    if (count($test)!=0){
        echo 2;
    }else{
       echo 3;
    }
}


//echo $contentStr;

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




function db_connect(){
    @$result = new mysqli('101.200.85.218', 'root','7DYKdev123!@#','test');
    if (!$result){
        echo 'Could not connect to database server';
        return false;
    }else{

        return $result;
    }
}