/**
 * Created by jh on 2015/10/31.
 */
var winWidth = 0;
var winHeight = 0;

var kidWidthP = 0.1;
var kidHeightP = 0.1;

var turkeyWidthP = 0.1;
var turkeyHeightP = 0.1;

var score=0;

var can1;
var can2;
var ctx1;
var ctx2;

var mx=0;
//var my=0;

var lastTime;
var deltaTime;

var mykid;
var myturkey;


function game(){
    init();
    gameloop();
}

function  init(){
    document.getElementById("start").setAttribute("style","display:none");
    document.getElementById("end").setAttribute("style","display:none");
     document.getElementById("result").setAttribute("style","display:none");
     document.getElementById("upload").setAttribute("style","display:none");
     document.getElementById("rank").setAttribute("style","display:none");
    document.getElementById("all_canvas").setAttribute("style","display:block");
    $("#imgshare").hide();
    $("#imglogo").show();
    mx= winWidth/2;
    score=0;
    
    kidWidth = winWidth * 0.1;
    kidHeight = winHeight * 0.1;

    can1 = document.getElementById("canvas1");//kid
    ctx1=can1.getContext('2d');
    if(IsPC()){
    can1.addEventListener('mousemove',onMouseMove,false);
    }else {
    can1.addEventListener("touchmove",touchMoveFunc, false);
    }
    can2 = document.getElementById("canvas2");//turkey
    ctx2=can2.getContext('2d');


    mykid=new kid();
    mykid.init();
    myturkey=new turkey();
    myturkey.init();

}

function onMouseMove(e){
    if(e.offsetX|| e.layerX){
        mx= e.offsetX==undefined? e.layerX: e.offsetX;
        if(mx<0){
        mx=0;
        }
        
        if(mx>winWidth){
        mx=winWidth-mykid.kidWidth;
        }
       // my= e.offsetY==undefined? e.layerY: e.offsetY;
    }
    console.log(mx);
}



//touchmove事件 
function touchMoveFunc(e) {  
    e.preventDefault(); //阻止触摸时浏览器的缩放、滚动条滚动等  
    var touch = e.touches[0];
    mx = touch.pageX-mykid.kidWidth/2;
    
     if(mx<0){
        mx=0;
        }
        
        if(mx>winWidth){
        mx=winWidth-mykid.kidWidth;
        }
    // y = touch.pageY - startY;

}  



function setScreen(){
    winHeight = document.documentElement.clientHeight;
    winWidth = document.documentElement.clientWidth;
    document.getElementById("bg").setAttribute("style","width:"+winWidth+"px;height:"+winHeight+"px");
    document.getElementById("bg").setAttribute("class","back");
    document.getElementById("all_canvas").setAttribute("style","width:"+winWidth+"px;height:"+winHeight+"px");
    document.getElementById("canvas1").setAttribute("width",winWidth);
    document.getElementById("canvas1").setAttribute("height", winHeight);
    document.getElementById("canvas2").setAttribute("width",winWidth);
    document.getElementById("canvas2").setAttribute("height", winHeight);

}

function gameloop(){
    if(!mykid.alive){
        gameover();
        return;
    }
    requestAnimFrame(gameloop);
    var now = Date.now();
    deltaTime = now - lastTime;
    lastTime = now;
//清理画布
    ctx2.clearRect(0,0,winWidth,winHeight);
    ctx1.clearRect(0,0,winWidth,winHeight);
//绘制新图

    isCrash();

    mykid.x=mx;

    mykid.draw();
    turkeyMonitor();
    myturkey.draw();

    document.getElementById("score").innerHTML=score;
}


function turkeyMonitor(){
    var num=0;
    for(var i=0;i<myturkey.num;i++){
        if(myturkey.alive[i]) num++;
    }
    if(num<15){
        sendturkey();
    }

    console.log(num);
}

function sendturkey(){
    for(var i=0;i<myturkey.num;i++){
        if(!myturkey.alive[i]){
            console.log(i);
            myturkey.born(i);
            break;
        }
    }
}


function  isCrash(){
    for(var i=0;i<myturkey.num;i++){
        if(myturkey.alive[i]){
            if((myturkey.y[i]+myturkey.turkeyHeight>mykid.y)&&(myturkey.x[i]+myturkey.turkeyWidth>mykid.x)&&(myturkey.x[i]<mykid.x+mykid.kidWidth)){
                if(myturkey.type[i]=="alive"){
                    myturkey.alive[i]=false;
                    score++;
                }else{
                   mykid.alive=false;
                }
            }
        }
    }
}

function  gameover(){
    document.getElementById("all_canvas").setAttribute("style","display:none");
    document.getElementById("result_score").innerHTML=score;
    
     $.ajax({
        type:"GET",
        url:"rank.php?score="+score,
        dataType:"json",
        success:function(data){

         document.title = "我接了"+score+"个火鸡，排名"+data["rank"]+",离大奖不远了，你也快来！";
        },
        error:function(data){
           // alert("error_all");

        }
 });
    
    $("#end").show();
    $("#result").slideDown("slow",function(){
        setTimeout(function(){$("#result").slideUp("slow",function(){$("#upload").slideDown("slow");});},1000);  
    });
    
    
   
}




function uploadresult(){
    $("#list").html("");
    
    if($("#name").val()&&$("#phone").val()){
     $.ajax({
        type:"GET",
        url:"turkey.php?name="+$("#name").val()+"&phone="+$("#phone").val()+"&score="+score,
        dataType:"json",
        success:function(data){
            //data=eval("("+data+")");
            $.each(data, function(i, n){
                if(i!=0){
                                $("#list").append("<ul><li class=\"ulname\">"+n.name+"</li><li class=\"ulphone\">"+n.phone+"</li><li class=\"ulrank\">"+n.score+"</li></ul>");
                }

            });

        },
        error:function(data){
            //alert("error_all");

        }
 });

    $("#upload").hide();
    $("#result").hide();
    $("#imgshare").show();
    $("#imglogo").hide();
 
    $("#rank").slideDown("slow");
    }else{
        alert("信息没有填写哦");
    }
}

function IsPC()
{
    var userAgentInfo = navigator.userAgent;
    var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; }
    }
    return flag;
}