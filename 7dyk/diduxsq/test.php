<?php

include 'vote.php';

//$action = new voteAction ();                  
$content = get_user("oHq4NwP51JZy_7Lrevg3h6cxTW1E");

$result=json_decode($content,true);

echo $result["nickname"];
            
  

    
    function get_user($openid) {
    
    $appid = "wx7d08599a2b86ac00";  
    $appsecret = "c868a69fefe6118503cdb3900133484e";  
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";  
   
    $ch = curl_init();  
    curl_setopt($ch, CURLOPT_URL, $url);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    $output = curl_exec($ch);  
    curl_close($ch);  
       
    $jsoninfo = json_decode($output, true);  
    $access_token = $jsoninfo["access_token"];  
    
    $url = " https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid";
        
    $result = https_request($url);

           
    return  $result;       		   
    

}
       function https_request($url)  
    {         
        $curl = curl_init();         
        curl_setopt($curl, CURLOPT_URL, $url);         
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);         
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);         
        $data = curl_exec($curl);         
        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}         
        curl_close($curl);         
        return $data;  
    }  


?>