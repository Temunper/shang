$(".del-ad").click(function () {
   var ad_id = $(this).attr("data-ad");
   console.log(ad_id);
   var r = confirm("是否删广告位？");
   var del = $(this);
   if (r === true){
       $.ajax({
           url:"/admin/ad/update_status",
           data:{
               ad_id:ad_id
           },
           dataType:"json",
           type:"POST",
           success:function (data) {
               data = JSON.parse(data);
               if (data.code === 200){
                   del.parent().parent().html("");
               }
               alert(data.data);
           }
       })
   }
});
$(".add-ad").click(function () {
    window.open("/admin/ad/plus");
});
$(".update-ad").click(function () {
    console.log(123);
    window.open("/admin/ad/content?ad_id=" + $(this).attr("data-ad"));
});