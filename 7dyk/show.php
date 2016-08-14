<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/6
 * Time: 11:33
 */
include_once ('choosefun.php');
@$file=$_FILES['image'];
@$age=$_POST['age'];
@$height=$_POST['height'];
@$gender=$_POST['gender'];
@$education=$_POST['education'];



$address=chooseFile($gender,$education,$height,$age);//性别，教育，身高，年龄
$filedir='img/'."$address[0]/$address[1]/$address[2]/$address[3]/";
$tem = scandir($filedir);
$numfile=count($tem)-2;
$name=mt_rand(1,$numfile);
$filename=$filedir.$name.'.jpg';


$addressSave=saveFile($gender,$education,$height,$age);
$fileSave='img/'."$addressSave[0]/$addressSave[1]/$addressSave[2]/$addressSave[3]/";
$temSave= scandir($fileSave);
$numname=count($temSave)-1;
$savename=$fileSave.$numname.'.jpg';

if(!move_uploaded_file($_FILES['image']['tmp_name'],$savename)){
    echo "error";
}
?>

<html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <!--link rel="stylesheet" type="text/css" href="css/index2.css" -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

    <title>报名成功</title>

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


    <style>
        body{
            margin: 0;
            padding: 0;
            width: 100%;
            background: url('img/bg4.jpg') no-repeat;
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
        .block{
            width:100%;
            margin-bottom:15px;
            height:30px;
            clear:both;
        }
        .block>span{
            width:80%;
            max-width: 80%;
            float: left;
            color: orange;
            line-height: 30px;
            font-weight: bold;
        }
    </style>
</head>

<body>
<form action="vote.php" method="post">
    <div id="page1">
        <div class="imgblock" style="text-align:center" >
            <img src="<?php echo $filename; ?>"/>
        </div>
        <div class="block">
                <span>如上图所示</span>
        </div>
    </div>
</form>
</body>
</html>
