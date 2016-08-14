window.requestAnimFrame = (function() {
	return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
		function( /* function FrameRequestCallback */ callback, /* DOMElement Element */ element) {
			return window.setTimeout(callback, 1000 / 60);
		};
})();

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


var winHeight=0;
var winWidth=0;

function setScreen(){
	winHeight = document.documentElement.clientHeight;
	winWidth = document.documentElement.clientWidth;
	document.getElementById("bg").setAttribute("style","width:"+winWidth+"px;height:"+winHeight+"px");
	document.getElementById("bg").setAttribute("class","back");
}

function getTime(){
    var d=new Date();
    return d.getTime();
}