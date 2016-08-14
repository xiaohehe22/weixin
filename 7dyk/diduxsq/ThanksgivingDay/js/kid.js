/**
 * Created by jh on 2015/10/31.
 */

var kid=function(){
    this.alive;
    this.x;
    this.y;
    this.kidWidth;
    this.kidHeight;
    this.kidpic=new Image();
}

kid.prototype.init=function(){
        this.alive=true;
        this.x=0;
    this.kidWidth=winWidth*kidWidthP;
       this.kidHeight=winHeight*kidHeightP;
    this.y=winHeight*(1-kidHeightP);
    this.kidpic.src="images/kid.png";
}

kid.prototype.draw=function(){
    ctx1.drawImage(this.kidpic,this.x,this.y,this.kidWidth,this.kidHeight);
}
