<?php
echo '<strong>Hello, SAE!</strong>';
$url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=JSy32wlHBJofWEOqUapbfQyfh-PORlDu3xNCqYZDyLS5IVCXFIxQex_m7KEPdnAN1hgOG793RD9yjRIphfSI9r-PIuCeqSHeP6VwNlQ_eVw";

function request_by_curl($remote_server, $post_data) {  
  $ch = curl_init();  
  curl_setopt($ch, CURLOPT_URL, $remote_server);  
  curl_setopt($ch, CURLOPT_POST, 1);  
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
  curl_setopt($ch, CURLOPT_USERAGENT, "qianyunlai.com's CURL Example beta");  
  $data = curl_exec($ch);  
  curl_close($ch);  
  
  return $data;  
}  

$post_data = '{"type":"image","offset":0,"count":10}';

echo request_by_curl($url, $post_data);