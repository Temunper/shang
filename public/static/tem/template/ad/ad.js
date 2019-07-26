$(".del-ad").click(function () {
   var ad_id = $(this).attr("data-ad");                 //获取要删除的ad_id
   var r = confirm("是否删广告位？");                  //警示框
   var del = $(this);
   if (r === true){
       $.ajax({                                         //ajax删除
           url:"/admin/ad/update_status",
           data:{
               ad_id:ad_id
           },
           dataType:"json",
           type:"POST",
           success:function (data) {
               data = JSON.parse(data);
               if (data.code === 200){
                   del.parent().parent().html("");      //删掉相关数据的dom元素
               }
               alert(data.data);
           }
       })
   }
});
$(".add-ad").click(function () {
    window.location.href = "/admin/ad/plus";
});
$(".update-ad").click(function () {
    window.location.href = "/admin/ad/content?ad_id=" + $(this).attr("data-ad");
});