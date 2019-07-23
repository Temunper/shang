$(".web-add").click(function () {
    window.open("/admin/website/plus");
});
$(".web-content").click(function () {
   window.open("/admin/website/content?website_id="+$(this).attr("data-website"));
});

$(".web-delete").click(function () {
   var  website_id = $(this).attr("data-website");
   var r = confirm("请问是否要删除站点");
   var del = $(this);
   if (r === true){
       $.ajax({
           url:"/admin/website/update_status",
           data:{
               website_id:website_id
           },
           dataType:"json",
           type:"POST",
           success:function (data) {
               data = JSON.parse(data);
               alert(data.data);
               if (data.code === 200) {
                   del.parent().parent().html("");
               }
           }
       })
   }


});