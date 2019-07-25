//弹出改名空格
$(".update").each(function () {
    $(this).click(function () {
        $(this).parent().parent().find(".change-name").css("visibility", "visible");
        $(this).parent().parent().find(".confirm-name").css("visibility", "visible");
    })
});

//修改提交
$(".confirm-name").each(function () {
    $(this).click(function () {
        var name = $(this).parent().find(".change-name").val();
        var theme_id = $(this).attr("data-theme");
        var upd = $(this);
        $.ajax({
            url: "/admin/theme/update",
            data: {
                name: name,
                theme_id: theme_id
            },
            dataType: "json",
            type: "POST",
            success: function (data) {
                data = JSON.parse(data);
                alert(data.data);
                if (data.code === 200) {
                    upd.parent().parent().find(".change-name").css("visibility", "hidden");
                    upd.css("visibility", "hidden");
                    upd.parent().find(".name-text").text(name);
                }
            }
        })
    })
});

//删除主题
$(".delete").click(function () {
    var theme_id = $(this).attr("data-theme");
    var r = confirm("是否要删除主题?");
    var del = $(this);
    if (r === true) {
        $.ajax({
            url: "/admin/theme/update_status",
            data: {
                theme: {
                    theme_id: theme_id
                }
            },
            dataType: "json",
            type: "POST",
            success: function (data) {
                data = JSON.parse(data);
                alert(data.data);
                if (data.code === 200) {
                    del.parent().parent().html("");
                }
            }
        })
    }
});

$(".theme-add").click(function () {
   window.location.href = "/admin/theme/plus";
});