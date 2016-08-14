<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

	<title>来一场说走就走的免费旅行</title>

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

	<script  src="js/jquery.min.js" type="text/javascript"></script>

    <style>
    body {
    margin:0;
    padding:0;
}
.container{
	width:100%;
}

.header{
	background-color: #85d2c1;
}

.header img{
	float:left;
	margin-left:5%;
	margin-top:1%;
	width:20%;
    border-radius:100px;
}
.title{
	float:right;
	margin-right: 5%;
	margin-top:2%;
	width:60%;
}
.clear{
	clear:both;
}
.header h1{
	color:white;
	font-size: 1.5em;
	margin-top: 0;
	padding-right: 0%;
}
.header h2{
	color:white;
	margin-top: 5%;
	width:100%;
	font-size:1em;
}
    
        
        .cardbody{
            margin:5%;            
        }
        .cardbody p{
            margin:3% 20%;            
        }
        
         .cardbody span{
              font-size: 1.2em;             
        }
        
        .cardbody input{
            height:1.5em; 
            font-size: 1.2em;    
            width:100%;
            border:1px solid  #85d2c1;
        }
        
        select,option{
            height:1.5em; 
            font-size: 1.2em;    
            width:100%;
            border:1px solid  #85d2c1;
        }
        
        .ok{           
            text-align:center;       
            
        }
         .ok input{
            width:20%;
            height:1.5em;
            font-size: 1.2em;  
             background-color:#85d2c1;
            border:1px solid  #85d2c1;
        }    
    </style>  
    
    <script language="javascript" type="text/javascript">  
    function getdata(){        
     if($("#membername").val()&&$("#name").val()&&$("#phone").val()&&$("#school").val()){  
        $.ajax({
        type:"GET",
        url:"vote_mid.php?&school="+$("#school").val()+"&membername="+$("#membername").val()+"&name="+$("#name").val()+"&phone="+$("#phone").val(),
        dataType:"json",
        success:function(data){            
            if(data.result=="error"){
            alert("报名失败");
                return;
            }
            window.location.href="http://2.diduxsq.sinaapp.com/member.php?num="+data.result;  
            // alert(data.result);//json name
        },
        error:function(data){
            alert("对不起，报名没有成功，请重新提交");

        }
 });

       
        
     } else{
         
         alert("有些信息没有填写");}
}
      


</script>
    
</head>

<body>
	
	<div class="container">
	    <!-- header part -->
	    <div class="header">
	        <img src="images/qdyk.jpg" alt="小圈">
	        <div class="title">
	            <h1>七点一刻</h1>
	            <h2>全班免费旅行</h2>
	        </div>
	        <div class="clear"><p></p></div>
	    </div>

	    <!-- body part -->
	    <div class="cardbody">   
  <p><span>学校:&nbsp;&nbsp;</span></p>
 <p><input type="text" id="school"></p>
            
 <p><span>班级:&nbsp;&nbsp;</span></p>
 <p><input type="text" id="membername"></p>   
            
 <p><span>负责人:&nbsp;&nbsp;</span></p> 
  <p><input type="text" id="name"></p>  
            
 <p><span>联系方式:&nbsp;&nbsp;</span></p>
 <p><input type="text" id="phone"></p> 
            
	</div>
            
 <p class="ok"><input type="button" value="提交" onclick="getdata()"></p> 
	  
	    <div class="clear"></div>
	   </div>
	   
        
        <div class="clear"></div>




</body></html>