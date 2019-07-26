$(".add-theme").click(function () {
   var name = $(".theme-name").val();
   $.ajax({
       url:"/admin/theme/add",
       data:{
           name:name
       },
       dataType:"json",
       type:"POST",
       success:function (data) {
           data = JSON.parse(data);
           alert(data.data);
       }
   })
});

$(".go-back").click(function () {
    window.location.href = "/admin/theme/theme";
});