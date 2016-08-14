<?php

        
$URL="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=dLna_m68RDVBiHg8KETliiGeAgNUvUD0LefGBX_seyCDgZ4al87KdZCRNiVkXPhVH5YetSx1V2P5gOCzgQhGsCTx-2-_OcMVjSpXfuiuh3pR0ogYKb9pswwjdX5SD4m6QYDjAIAHPU";
$data = '{
    "touser":"oabOfs674QelTyvIUELn3jGdw2D8",
    "msgtype":"text",
    "text":
    {
         "content":"Hello World"
    }
}';

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $URL); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

$info = curl_exec($ch);  


?>