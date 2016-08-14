<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/3
 * Time: 10:36
 */
header("Content-type:text/html;charset=utf-8");
include_once ('functions.php');

@$name=$_POST['name'];
@$school=$_POST['school'];
@$phone=$_POST['tel'];

//
//直接浏览到该页面
if(count($_POST)<3){

    echo '  请扫描上述微信公众号关注后报名!  ';
    exit;
}
if(!filled_out($_POST)){
    echo '   请扫描上述微信公众号关注后报名 ';
    exit;
}else{
    $conn=db_connect();
    mysqli_query($conn,"set names 'utf8'");
    $query= "select id from qixi where phone = '$phone'";
    $result=$conn->query($query);
    if(!$result){
        echo 'error';
        exit;
    }
    $test=$result->fetch_array();
    if (count($test)!=0){
       // echo  "您已报名该活动，您的id是".$test[0];
       // exit;
    }

    $query="insert into qixi(name,school,phone) values ('$name','$school','$phone')";
    $result=$conn->query($query);

    $query= "select id from qixi where phone = '$phone'";
    //mysqli_query("set names 'utf8'");
    $result=$conn->query($query);
    $test=$result->fetch_object();
    $id=$test->id;
    mysqli_close($conn);
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
            background: url('bg.jpg') no-repeat;
            background-size: 100%;
            background-color: #fbe3c9;
            font-size: 1rem;
        }

        #page1{
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            padding: 0 20px;
        }
        .imgblock{
   			 width:100%;
   			 margin-bottom:0px;
   			 height:100px;
   			 clear:both;
		}
		.imgblock>img{
   			 width:60px;
   			 margin:auto;
    		clear:both;
		}
        .block{
            width:100%;
            margin-bottom:0px;
            height:60px;
            clear:both;
        }
        .block2{
            width:100%;
            margin-bottom:25px;
            height:60px;
            clear:both;
        }
        .block3{
            width:100%;
            margin-bottom:0px;
            height:60px;
            clear:both;
        }
        .block>.pos1{
            width:80%;
            max-width: 80%;
            float: right;
            color: #0498be;
            line-height: 30px;
            font-weight: bold;
        }
        .block2>.pos2{
            width:80%;
            max-width: 80%;
            float: right;
            color: #0498be;
            line-height: 30px;
            font-weight: bold;
        }
        .block3>.pos3{
            width:80%;
            max-width: 80%;
            float: right;
            color: #0498be;
            line-height: 30px;
            font-weight: bold;
        }
    </style>
</head>

<body>
<form action="vote.php" method="post">
    <div id="page1">
        <div class="block">
            <span>牛郎织女收到你的请求啦</span>
            <span class="pos1">你的鹊桥编号是<?php echo $id;?>号</span>
        </div>
        <div class="block2">
            <span>你的小伙伴如何支持</span>
            <span class="pos2">让ta回复【支持<?php echo $id;?>】号</span><br/>
            <span class="pos2">即可向你投递一只喜鹊</span>
        </div>
        <div class="block3">
            <span>如何查看自己的鹊桥喜鹊数量</span>
            <span class="pos3">回复【查看<?php echo $id;?>】</span>
            <span class="pos3">即可查看现有喜鹊数量及排名</span>
        </div>
        <div class="imgblock" style="text-align:center" >
            <img src="bg3.jpg"/>
        </div>
    </div>
</form>
</body>
</html>