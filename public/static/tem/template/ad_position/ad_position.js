$(".del-adp").click(function () {
    var ad_id = $(this).attr("data-adp");
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
$(".add-adp").click(function () {
    window.open("/admin/ad_position/plus");
});
$(".update-adp").click(function () {
    console.log(123);
    window.open("/admin/ad_position/content?ad_position_id=" + $(this).attr("data-adp"));
});