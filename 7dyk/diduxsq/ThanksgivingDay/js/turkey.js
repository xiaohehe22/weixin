/**
 * Created by jh on 2015/10/31.
 */

var turkey=function(){
    this.alive =[];
    this.x=[];
    this.y=[];
    this.turkeyWidth=0;
    this.turkeyHeight=0;
    this.spd=[];
    this.type=[];
    this.alivepic=new Image();
    this.deadpic=new Image();
}

turkey.prototype.num=30;

turkey.prototype.init=function(){
    for(var i=0;i<this.num;i++){
        this.alive[i]=false;
        this.x[i]=0;
        this.y[i]=0;
        this.spd[i]=Math.random()*0.02+0.005;//[0.005-0.025];
        //console.log(i+" "+this.spd[i]);
        this.type[i]="";
    }
    this.turkeyWidth=winWidth*turkeyWidthP;
    this.turkeyHeight=winHeight*turkeyHeightP;
    this.alivepic.src="images/turkey_alive.png";
    this.deadpic.src="images/turkey_dead.png";
}

turkey.prototype.draw=function(){
    for(var i=0;i<this.num;i++){
        //移动活着的火鸡
        if(this.alive[i]){
            if(this.type[i]=="alive"){
                var pic=this.alivepic;
            }else{
                var pic=this.deadpic;
            }
           // console.log(i+" "+this.spd[i]);
            this.y[i]+=this.spd[i]*10*deltaTime*(1+score/40);
            //console.log(i+" "+this.y[i]);

            ctx2.drawImage(pic,this.x[i],this.y[i],this.turkeyWidth,this.turkeyHeight);
            if(this.y[i]>winHeight*(1-turkeyHeightP)){
                this.alive[i]=false;
            }
        }
    }
}

turkey.prototype.born=function(i){
    this.x[i]=Math.random()*winWidth*(1-turkeyWidthP);
    console.log(i+" born "+this.x[i]);
    this.y[i]=0;

    this.alive[i]=true;
    var ran = Math.random();
    var p=0.3;    
    
    if(score<50){
    p=0.3+score/200;
    }else{
    p=0.6;
    }
    if(ran<0.3){
        this.type[i]="dead";
    }else{
        this.type[i]="alive";
    }

}
