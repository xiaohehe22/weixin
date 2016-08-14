<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/12
 * Time: 16:31
 */
?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<!--link rel="stylesheet" type="text/css" href="css/index2.css" -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

	<title>报名</title>

	<!-- Set render engine for 360 browser -->
	<meta name="renderer" content="webkit">

	<!-- No Baidu Siteapp-->
	<meta http-equiv="Cache-Control" content="no-siteapp">

	<link rel="icon" type="image/png" href="/images/favicon.png">

	<!-- Add to homescreen for Chrome on Android -->
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="icon" sizes="192x192" href="/images/app-icon72x72@2x.png">

	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="stuprice">
	<link rel="apple-touch-icon-precomposed" href="/images/app-icon72x72@2x.png">

	<!-- Tile icon for Win8 (144x144 + tile color) -->
	<meta name="msapplication-TileImage" content="/images/app-icon72x72@2x.png">
	<meta name="msapplication-TileColor" content="#0e90d2">


    <script src="//cdn.bootcss.com/jquery/3.1.0/jquery.slim.js"></script>
    <script language="javascript" type="text/javascript">
     function getdata(){
         if($('#name').val() && $('#school').val() && $("#tel").val()){
             return true;
         }else{
             alert('请完善信息！');
             return false;
         }
     }
</script>


<style>

body{
    margin: 0;
    padding: 0;
    width: 100%;
    background: url('bg4.jpg') no-repeat;
    background-size: 100%;
    background-color: #2c2c2c;
    font-size: 1rem;
}

#page1{
    position: absolute;
    top: 300px;
    left: 0;
    right: 0;
    padding: 0 20px;
}
.block{
    width:100%;
    margin-bottom:15px;
    height:30px;
    clear:both;
}
.imgblock{
    width:100%;
    margin-bottom:30px;
    height:100px;
    clear:both;
}
.imgblock>img{
    width:100px;
    margin:auto;
    clear:both;
}
.block>span{
    width:40%;
    max-width: 40%;
    float: left;
    color: orange;
    line-height: 30px;
    font-weight: bold;
}
.block>.text{
    width: 50%;
    max-width: 50%;
    float: right;
    height:30px;
    line-height: 30px;
    padding: 2px 10px;
    outline: none;
    border:none;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}
.block>.submit{
    font-size: 1rem;
    font-weight:600;
    display: block;
    width:100%;
    margin:30px auto 0;
    background-color: orange;
    height:30px;
    color:white;
    line-height: 30px;
    outline: none;
    border:0;
    border-radius: 5px;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}
</style>
</head>

<body>
    <form action="vote.php" method="post">
        <div id="page1">
            <div class="imgblock" style="text-align:center" >
                <img src="bg3.jpg"/>
            </div>
            <div class="block">
                <span>最想和谁看电影：</span>
            	<input class="text" type='text' name ='name'id="name" placeholder="输入姓名"/>
            </div>
            <div class="block">
                <span>最想看的电影：</span>
                <input class="text" type='text' name ='school' id="school" placeholder="输入电影"/>
            </div>
            <div class="block">
                <span>联系方式：</span>
                <input  class="text" type='text' name ='tel' id="tel" placeholder="输入手机号"/>
            </div>
            <div class="block">
               <input  class="submit" type="submit" value="提  交" onclick="return getdata()">
            </div>
     	</div>
    </form>
</body>
</html>