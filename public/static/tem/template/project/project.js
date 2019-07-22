//地区三级联动
$(function () {
    initProvince("province");
    initProvince("d_province");
});
$("#province").bind('change', function () {
    var proid = $("#province option:selected").val()
    onProvinceChange(proid, "city")
});
$("#d_province").bind('change', function () {
    var proid = $("#d_province option:selected").val()
    onProvinceChange(proid, "d_city")
});
$("#city").bind('change', function () {
    var city = $("#city option:selected").val()
    onCityChange(city, "country")
});
$("#d_city").bind('change', function () {
    var city = $("#d_city option:selected").val()
    onCityChange(city, "d_country")
});


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
$(".first-class").bind("change", function () {
    var $son = $(this).find('option:selected').attr('data-son');
    var html = ['<option value=""></option>'];
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
    $('.second-class').html(html);
});
$(".d_first-class").bind("change", function () {
    var $son = $(this).find('option:selected').attr('data-son');
    var html = ['<option value=""></option>'];
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
    $('.d_second-class').html(html);
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

//提交添加
$(".submit-a").click(function () {
    var class_id = $(".second-class option:selected").val()
    if (class_id === 0) {
        class_id = null;
    }
    var province = $("#province option:selected").val();
    var city = $("#city option:selected").val();
    var country = $("#country option:selected").val();
    var area = getNum(province, city, country);
    $.ajax({
        url: "/admin/project/add",
        data: {
            project: {
                name: $("#name").val(),
                abbr: $("#abbr").val(),
                pattern: $("#pattern").val(),
                crowd: $("#crowd").val(),
                area: area,
                client_phone: $("#client_phone").val(),
                "num_400": $("#400").val(),
                company_name: $("#company_name").val(),
                company_addr: $("#company_addr").val(),
                superiority: $("#superiority").val(),
                analysis: $("#analysis").val(),
                prospect: $("#prospect").val(),
                summary: $("#summary").val(),
                contact: $("#contact").val(),
                class_id: class_id,
                money: $("#money option:selected").val(),
                keywords: $("#keywords").val(),
                description: $("#description").val(),
                kf_type: $("#kf_type").val()
            },
            client_name: $("#client_name").val()
        },
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            data = jQuery.parseJSON(data);
            if (data.pass === undefined)
                alert(data.data);
            else alert("客户的账号生成：" + jQuery.parseJSON(data.pass));
        }
    })
});
//取消
$(".submit-b").click(function () {
    $(".add-page").css('visibility', 'hidden');
    $(".list-page").css('filter', 'blur(0px)')
});
//添加按钮
$(".add-project").click(function () {

});

$(".project-content").each(function (index) {
    $(this).click(function () {
        window.open("/admin/project/content?project_id="+data_project[index].project_id);
    });
});
//查看详情
// $(".project-content").each(function (index) {
//     $(this).click(function () {
//         var data = data_project[index];
//         var clas = $(".all-class").attr('data-all-class');
//         set_data(data, JSON.parse(clas));
//         $(".content-page ").css('visibility', 'visible');
//         $(".list-page").css('filter', 'blur(2px)')
//     });
// });

$(".d-submit-b").click(function () {
    $(".content-page ").css('visibility', 'hidden');
    $(".list-page").css('filter', 'blur(0px)')
});

//确认修改
$(".d-submit-a").click(function () {
    var class_id = $(".d_second-class option:selected").val()
    if (class_id === 0) {
        class_id = null;
    }
    var province = $("#d_province option:selected").val();
    var city = $("#d_city option:selected").val();
    var country = $("#d_country option:selected").val();
    var area = getNum(province, city, country);
    $.ajax({
        url: "/admin/project/update",
        data: {
            project_id: $("#d_name").attr("data-project-id"),
            name: $("#d_name").val(),
            abbr: $("#d_abbr").val(),
            pattern: $("#d_pattern").val(),
            crowd: $("#d_crowd").val(),
            area: area,
            client_phone: $("#d_client_phone").val(),
            "num_400": $("#d_400").val(),
            company_name: $("#d_company_name").val(),
            company_addr: $("#d_company_addr").val(),
            superiority: $("#d_superiority").val(),
            analysis: $("#d_analysis").val(),
            prospect: $("#d_prospect").val(),
            summary: $("#d_summary").val(),
            contact: $("#d_contact").val(),
            class_id: class_id,
            money: $("#d_money option:selected").val(),
            keywords: $("#d_keywords").val(),
            description: $("#d_description").val(),
            kf_type: $("#d_kf_type").val()

        },
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            data = jQuery.parseJSON(data);
            alert(data.data);
        }
    })
});

//渲染详情页
function set_data(data, clas) {
    var class_id = data.class_id;
    var f_class_id = null;
    var area = data.area;
    area = getPlace(area);
    for (i = 0; i < clas.length; i++) {
        if (clas[i].son !== null) {
            var son = clas[i].son;
            for (k = 0; k < son.length; k++) {
                if (son[k].class_id === class_id) {
                    f_class_id = clas[i].class_id;
                }
            }
        }
    }
    $(".d_first-class").val(parseInt(f_class_id)).trigger('change');
    $(".d_second-class").val(parseInt(class_id));
    $("#d_province").val(parseInt(area.province)).trigger('change');
    $("#d_city").val(area.city).trigger('change');
    $("#d_country").val(area.country);
    $("#d_name").val(data.name).attr('data-project-id', data.project_id);
    $("#d_abbr").val(data.abbr);
    $("#d_pattern").val(data.pattern);
    $("#d_crowd").val(data.crowd);
    $("#d_client_phone").val(data.client_name);
    $("#d_400").val(data.num_400);
    $("#d_company_name").val(data.company_name);
    $("#d_company_addr").val(data.company_addr);
    $("#d_superiority").val(data.superiority);
    $("#d_analysis").val(data.analysis);
    $("#d_prospect").val(data.prospect);
    $("#d_summary").val(data.summary);
    $("#d_contact").val(data.contact);
    $("#d_money").val(data.money);
    $("#d_keywords").val(data.keywords);
    $("#d_description").val(data.description);
    $("#d_kf_type").val(data.kf_type);
    $("#d_client_name").val(data.client_name);
    $(".binding-client").attr('data-client-id', data.client_id).attr('data-project-id', data.project_id);
}

//解除客户项目绑定
$("#binding-btn").click(function () {
    var project_id = $(".binding-client").attr('data-project-id');
    var client_id = $(".binding-client").attr('data-client-id');
    $.ajax({
        url: "/admin/clientproject/unbinding",
        data: {
            project_id: project_id,
            client_id: client_id
        },
        dataType: "json",
        type: "POST",
        success: function (data) {
            data = jQuery.parseJSON(data);
            alert(data.data);
        }
    })
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
