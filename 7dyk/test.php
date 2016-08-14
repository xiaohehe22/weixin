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
            if($('#age').val() && $("#height").val()){
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
            width:20%;
            max-width: 20%;
            float: left;
            color: orange;
            line-height: 30px;
            font-weight: bold;
        }
        .block>.text{
            width: 70%;
            max-width: 70%;
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

<div id="page1">
    <form action="show.php" method="post" enctype = 'multipart/form-data' >
        <div class="block" style="text-align:center" >
            <span>您的照片：</span>
            <input type='hidden' name='MAX_FILE_SIZE' value='2621114' />
            <input type="file" name="image" ID="fupPhoto"/>
        </div>
    </form>
        <div class="block">
            <span>性别：</span>
            <input type=radio name="gender" value="男">男

            <input type=radio name="gender" value="女">女
        </div>
        <div class="block">
            <span>身高：</span>
            <input class="text" type='text' name ='height' id="height" placeholder="输入升高：如170"/>
        </div>
        <div class="block">
            <span>年龄：</span>
            <input  class="text" type='text' name ='age' id="age" placeholder="输入年龄：如22"/>
        </div>
        <div class="block">
            <span>学历：</span>
            <select name="education" id= "education">
                <option value="本科">本科</option>
                <option value="硕士">硕士</option>
                <option value="博士">博士</option>
                <option value="大专">大专</option>
                <option value="大专以下">大专以下</option>
            </select>
        </div>
        <div class="block">
            <input  class="submit" type="submit" value="提  交" onclick="return getdata()">
        </div>
        </form>
</div>

</body>
</html>