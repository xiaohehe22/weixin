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
            background: url('bg.jpg') no-repeat;
            background-size: 100%;
            background-color: #fbe3c9;
            font-size: 1rem;
        }

        #page1{
            position: absolute;
            top:300px;
            left: 0;
            right: 0;
            padding: 0 20px;
        }
        .block{
            width:100%;
            margin-bottom:30px;
            height:30px;
            clear:both;
            padding: 8px;
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
            width:100%;
            max-width: 100%;
            float: left;
            color: #0498be;
            line-height: 30px;
            font-weight: bold;
            text-align:center;

        }
        .block>.text{
            width: 90%;
            max-width: 90%;
            float: left;
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
            width:20%;
            margin:30px auto 0;
            background-color: #0498be;
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
        <div class="block">
            <span>你七夕想要和谁去看电影呢？</span><br/>
            <input class="text" type='text' name ='name'id="name" placeholder="输入姓名"/>
        </div>
        <div class="block">
            <span>你最想和Ta看的电影是什么？</span><br/>
            <input class="text" type='text' name ='school' id="school" placeholder="输入电影名称"/>
        </div>
        <div class="block">
            <span>联系方式</span>
            <input  class="text" type='text' name ='tel' id="tel" placeholder="输入手机号"/>
        </div>
        <div class="block">
            <input  class="submit" type="submit" value="提  交" onclick="return getdata()">
        </div>
        <div class="imgblock" style="text-align:center" >
            <img src="bg3.jpg"/>
        </div>
    </div>
</form>
</body>
</html>