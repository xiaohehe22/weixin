<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">

	<title>帮我们班获得免费旅游机会,支持-><?php echo $_GET['num'];?>号</title>

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
text-align:center;       
            
            margin:3% 5%;            
        }
         .cardbody img{
            width:50%;           
        }
        
         .cardbody span{
            color:red;           
        }
        

    </style>  
    
</head>

<body>
	 <div  style="width:0px; height:0px; overflow:hidden"><img src="images/bt.jpeg" ></div>
	<div class="container">
	    <!-- header part -->
	    <div class="header">
	        <img src="images/qdyk.jpg" alt="小圈">
	        <div class="title">
	            <h1>班级免费旅游机会</h1>
	            <h2>编号</h2>
	        </div>
	        <div class="clear"><p></p></div>
	    </div>

	    <!-- body part -->
	    <div class="cardbody">   
            
            
            
            <p>恭喜你报名成功,你的编号为<span><?php echo $_GET['num'];?></span> <br/>
                关注下方二维码 <br/>
                后台回复“规则”，查看获取全班免费旅游的攻略<br/>
                后台回复“报名”，争取全班免费旅游机会<br/>
后台回复“支持<span><?php echo $_GET['num'];?></span> ”，为班级投票<br/>
后台回复"查看<span><?php echo $_GET['num'];?></span> ”，查询投票数 </p>
            <p>
              <img  src="images/qdyker.jpg"/>  
            </p>
             <p>
                点击右上角，晒出你的报名编号，争取身边的小伙伴的支持吧~
            </p>

	   </div>
        
        
	    </div>  
        
       




</body></html>