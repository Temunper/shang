



//分类的二级联动
$(".down-class").bind("change", function () {
    var $son = $(this).find('option:selected').attr('data-son');
    var html = ['<option value="">======</option>'];
    if ($son !== '') {
        $son = JSON.parse($son);
        for (x in $son) {
            var $item = $son[x];
            var option = document.createElement('option');
            option.value = $item['class_id'];
            option.innerHTML = $item['name'];
            html.push(option);
        }
    }
    $('.down-son-class').html(html);
});




//分类触发
$(".project-search").click(function () {
    var class_id_first = $(".down-class").find('option:selected').val();
    var class_id_second = $(".down-son-class").find('option:selected').val();
    var project_name = $(".search-project-content").val();
    var client_name = $(".search-client-content").val();
    var class_id;
    class_id_second !== '' ? class_id = class_id_second : class_id = class_id_first;
    window.location.href = "/admin/project/project?class_id=" + class_id + "&project_name=" + project_name + "&client_name=" + client_name;
});


//添加按钮
$(".add-project").click(function () {
   window.open("/admin/project/plus");
});

$(".project-content").each(function (index) {
    $(this).click(function () {
        window.open("/admin/project/content?project_id="+data_project[index].project_id);
    });
});

//删除项目
$(".project-del").each(function (index) {
    $(this).click(function () {
        var del = $(this);
        var r = confirm("确定删除" + data_project[index].name + "?");
        if (r === true) {
            $.ajax({
                url: "/admin/project/update_status",
                data: {
                    project_id: data_project[index].project_id
                },
                dataType: "json",
                type: "POST",
                success: function (data) {
                    data = jQuery.parseJSON(data);
                    if (data.code === 200){
                        del.parent().parent().html("");
                    }
                    alert(data.data);
                }
            })
        }
    });
});
