//分类的二级联动
$(".down-class").bind("change", function () {
    var $son = $(this).find('option:selected').attr('data-son');        //获取下拉框的值
    var html = ['<option value="">======</option>'];
    if ($son !== '') {
        $son = JSON.parse($son);
        for (x in $son) {                                   //遍历出所有的树叶值
            var $item = $son[x];
            var option = document.createElement('option');  //创建dom元素
            option.value = $item['class_id'];                   //编辑dom元素属性
            option.innerHTML = $item['name'];
            html.push(option);
        }
    }
    $('.down-son-class').html(html);
});


//分类触发
$(".project-search").click(function () {            //得到查询条件的值
    var class_id_first = $(".down-class").find('option:selected').val();
    var class_id_second = $(".down-son-class").find('option:selected').val();
    var project_name = $(".search-project-content").val();
    var client_name = $(".search-client-content").val();
    var binding = $(".binding-client option:selected").val();
    var class_id;
    class_id_second !== '' ? class_id = class_id_second : class_id = class_id_first;
    window.location.href = "/admin/project/project?class_id=" + class_id + "&project_name=" + project_name + "&client_name=" + client_name+"&bind_code="+binding;
});


//添加按钮
$(".add-project").click(function () {
    window.location.href = "/admin/project/plus";
});

$(".project-content").each(function (index) {
    $(this).click(function () {
        window.location.href = "/admin/project/content?project_id=" + data_project[index].project_id;
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
                    if (data.code === 200) {
                        del.parent().parent().html("");
                    }
                    alert(data.data);
                }
            })
        }
    });
});

//   重新生成密码
$(".new-pass").click(function () {
    var client_name = $(this).attr("data-client");
    var client_id = $(this).attr("data-id");
    var r = confirm("确定重新生成'" + client_name + "'的密码吗？");
    if (r === true) {
        window.location.href = "/admin/client/pass?client_id="+client_id+"&client_name="+client_name;
    }
});
