

function getdata()
{ 

    if($("#data1").val()&&$("#data2").val()){
        
         $.ajax({
        type:"GET",
        url:"luck.php?data1="+$("#data1").val()+"&data2="+$("#data2").val()+"&timestamp="+new Date().getTime() ,
        dataType:"json",
        success:function(data){
            //data=eval("("+data+")");
            $.each(data, function(i, n){
                if(n.number==""){
                     $("ul").append("<li>信息填错了吗，没有找到你的福利哦</li>");
                }else{
                $("ul").append("<li>你的电影票序列号为："+n.number+"，密码为："+n.password+"</li>");
                }
            });

        },
        error:function(data){
            alert("error_all");
            //  alert(data);

        }
 });

    }else{
 
        $("ul").append("<li>获取福利的信息没有填写完整哦</li>");
    }
}
