<?php
include_once ('functions.php');
define("TOKEN", "weixin");
define("appid","wxb19fa004abdf4374");
define("appsecret","82be22c5f63ac37bd59b528fe7482f9e");
$appid=appid;
$appsecret=appsecret;

$conn=db_connect();
$query="select * from access";

$result=$conn->query($query);

$access=$result->fetch_object();
$access_time=$access->access_time;

$time=time();
echo $time;
echo strlen('F9YnvOtx-wxLqe2XvIeVT0xWJyt8c_mII-kL1sEPtnufeUxGoEse5LXXtJAnjTFtM-Oa4GPOuLDIMmW_C9f3rKmVTuiEMb9vRKdIb5oYaFewZi04WegQLHTaa29Gsma0VMHbABANEZ');
if (($time-$access_time)>7000){
    $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
    $res=https_request($url);
    $result=json_decode($res,true);
    $access_token=$result["access_token"];
    $query="update access set access_time = '$time' , access_token = '$access_token'";
    $result=$conn->query($query);
    echo $access_token;
}else{
    echo $access->access_token;
}
