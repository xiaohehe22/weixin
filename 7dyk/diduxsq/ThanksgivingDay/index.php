<?php
define("GREETING","Hello world!");
?>

<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="UTF-8">
<meta name="viewport"content="target-densitydp =device-dpi, width=device-width ,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <title>感恩节，接火鸡赢大餐！</title>
    <link  rel="stylesheet" href="css/reset.css" type="text/css">
    <link  rel="stylesheet" href="css/index.css" type="text/css">
</head>
<script src="js/jquery-1.11.3.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        setScreen();
        $("#logo").slideDown("slow",function(){
            $("#start").slideDown("slow");
        });
    });
    
</script>

<body>
    <audio controls="controls" autoplay="autoplay" loop="loop" style="display:none">
			<source src="music.mp3" type="audio/mpeg">
		</audio>   
    
    <div  style="width:0px; height:0px; overflow:hidden"><img src="images/turkey_alive.png" ></div>
<div id="bg">
    <div id="logo">
    <img id="imglogo" src="images/logo.png"/>
    <img id="imgshare" src="images/share.png"/>
    </div>
    <div id="start">
        <img src="images/start_button.png" onclick="game()"/>
    </div>
    <div id="all_canvas">
        <canvas id="canvas1"></canvas>
        <canvas id="canvas2"></canvas>
        <div id="score"></div>
    </div>

    <div id="end">
        <div id="share"></div>
        <div id="result">
            <div id="result_score"></div>
        </div >
        <div id="upload">
            <input id="name" type="text"/>
            <input id="phone" type="text"/>
            <img id="upload1" src="images/upload_button.png" onclick="uploadresult()"/>
             <img id="upload2" src="images/start_again.png" onclick="game()"/>
        </div>
    <div id="rank">
        <div id="list"></div>
         <img src="images/start_again.png" onclick="game()"/>
     <a href="http://2.diduxsq.sinaapp.com/diduxsq.html">关注帝都学生圈，回复“感恩节”查看奖品</a>
    </div>

    </div>
 </div>


<script src="js/commonFunctions.js" type="text/javascript"></script>
<script src="js/kid.js" type="text/javascript"></script>
<script src="js/turkey.js" type="text/javascript"></script>
<script src="js/game.js" type="text/javascript"></script>


</body>
</html>