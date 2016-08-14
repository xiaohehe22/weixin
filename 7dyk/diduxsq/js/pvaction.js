

function getdata()
{ $("ul").html("");

    if($("#datepicker1").val()&&$("#datepicker2").val()){
        
         $.ajax({
        type:"GET",
        url:"pv.php?start="+$("#datepicker1").val()+"&end="+$("#datepicker2").val()+"&timestamp="+new Date().getTime() ,
        dataType:"json",
        success:function(data){
            //data=eval("("+data+")");
            $.each(data, function(i, n){
                $("ul").append("<li>"+n.name+":"+n.num+"</li>");

            });

        },
        error:function(data){
            alert("error_all");
            //  alert(data);

        }
 });

    }else{
 $.ajax({
        type:"GET",
        url:"pv.php?timestamp="+new Date().getTime() ,
        dataType:"json",
        success:function(data){
            alert(data);
            //data=eval("("+data+")");
            $.each(data, function(i, n){
                if(i==0){
                continue;
                }
                $("ul").append("<li>"+n.name+":"+n.num+"</li>");

            });

        },
        error:function(data){
            alert("error_all");
            //  alert(data);

        }
 });
    }
}
