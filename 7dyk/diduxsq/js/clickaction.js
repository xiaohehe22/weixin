
function addclick(id)
{
    $.ajax({
        type:"POST",
        url:"clickdata.php?name="+id+"&timestamp="+new Date().getTime(),
        dataType:"json",
        success:function(data){
            // alert(data.name);
            //window.location.href="http://mp.weixin.qq.com/s?__biz=MzAwMTMxODA4OQ==&mid=204288478&idx=1&sn=fb13da9e6cfca56676ecae4ba3de0fbe&scene=18#rd";
        },
        error:function(data){
            // alert(data);
            // alert("error1111");
        }
    });
}



//$(document).ready(function(){
// alert('ready');
//  var exp = new Date();
//  exp.setTime(exp.getTime() - 1);
// alert(exp);
// document.cookie= "";
//  getallclick();
//});


function getclick(id)
{

    $.ajax({
        type:"GET",
        url:"clickdata.php?name="+id+"&timestamp="+new Date().getTime(),
        dataType:"json",
        success:function(data){
            // alert(data.name);
            // alert("success1111");
        },
        error:function(data){
            //  alert(data);
            // alert("error1111");
        }
    });
}

function getallclick()
{

    $.ajax({
        type:"GET",
        url:"clickdata.php?name=all"+"&timestamp="+new Date().getTime() ,
        dataType:"json",
        success:function(data){
            // alert("success_all");
            //   data = eval("("+data+")");
            $.each(data, function(i, n){

                var aid = document.getElementById(n.name);
                if(aid!=null){
                    var spanid=aid.lastElementChild;
                    spanid.innerHTML= n.num;
                }
            });

        },
        error:function(data){
            //  alert("error_all");
            //  alert(data);

        }
    });
}
