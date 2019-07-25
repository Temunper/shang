$(".add-clas").click(function () {
    window.location.href = "/admin/clas/plus";
});
$(".update-clas").click(function () {
    console.log(123);
    window.location.href = "/admin/clas/content?class_id=" + $(this).attr("data-class");
});

$(".del-clas").click(function () {
    var class_id = $(this).attr("data-class");

    var r = confirm("确定删除分类:" + $(this).attr("data-name"));
    var del = $(this);
    if (r === true) {
        $.ajax({
            url: "/admin/clas/delete",
            data: {
                class_id:class_id
            },
            dataType: "json",
            type: "POST",
            success: function (data) {
                data = JSON.parse(data);
                if (data.code === 200){
                    del.parent().parent().html("");
                }
                alert(data.data);
            }
        })
    }
});