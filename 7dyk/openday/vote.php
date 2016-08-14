<?php
/**
 * Created by PhpStorm.
 * User: CC
 * Date: 2016/8/3
 * Time: 10:36
 */


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
                <img src="bg3.jpg"/>
        </div>  
        <div class="block">
                <span>






<?php
include_once ('functions.php');

@$name=$_POST['name'];
@$school=$_POST['school'];
@$phone=$_POST['tel'];

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
    $query= "select id from test where phone = '$phone'";
    $result=$conn->query($query);
    if(!$result){
        echo 'error';
        exit;
    }
    $test=$result->fetch_array();
    if (count($test)!=0){
        echo  "您已报名该活动，您的id是".$test[0];
        exit;
    }

    $query="insert into test(name,school,phone) values ('$name','$school','$phone')";
    $result=$conn->query($query);

    $query= "select id from test where phone = '$phone'";
    $result=$conn->query($query);
    $test=$result->fetch_object();
    $id=$test->id;
    mysqli_close($conn);
    echo '1.恭喜您七夕活动报名成功，您的ID是'.$id;
}
?>
                    <br/>2.7点一刻后台回复：支持<?php echo $id;?>
                    <br/>3.7点一刻后台回复：查看<?php echo $id;?>
            </span>
        </div>
    </div>
</form>
</body>
</html>