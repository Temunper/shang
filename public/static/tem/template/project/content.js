// 页面渲染
$(function () {
    initProvince("d_province");
    var clas = $(".class-data").attr("data-all-class");
    clas = JSON.parse(clas);
    var class_id = $(".class-data").attr("data-class");
    var f_class_id = null;
    var area = $(".area-data").attr("data-place");
    area = getPlace(area);
    for (i = 0; i < clas.length; i++) {
        if (clas[i].son !== null) {
            var son = clas[i].son;
            for (k = 0; k < son.length; k++) {
                if (son[k].class_id == class_id) {
                    f_class_id = clas[i].class_id;
                }
            }
        }
    }
    // console.log(f_class_id);return false;
    $(".d_first-class").val(parseInt(f_class_id)).trigger('change');
    $(".d_second-class").val(parseInt(class_id));
    $("#d_province").val(parseInt(area.province)).trigger('change');
    $("#d_city").val(area.city).trigger('change');
    $("#d_country").val(area.country);
    $("#d_money").val($(".get-money").attr("data-money"));
});
//地区的三级联动
$("#d_province").bind('change', function () {
    var proid = $("#d_province option:selected").val();
    onProvinceChange(proid, "d_city")
});
$("#d_city").bind('change', function () {
    var city = $("#d_city option:selected").val();
    onCityChange(city, "d_country")
});

//分类的二级联动
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

//提交修改
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
            project_id: $(".data-project-id").attr("data-project-id"),
            name: $("#d_name").val(),
            yw_name:$("#d_yw_name").val(),
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

//解除绑定
$("#binding-btn").click(function () {
    var r = confirm("是否确定解除绑定？");
    if (r === true) {
        var project_id = $(".binding-client").attr('data-project');
        var client_id = $(".binding-client").attr('data-client');
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
                if (data.code === 200){
                    $("#d_client_name").val("");
                }
                alert(data.data);
            }
        })
    }
});

$(".d-submit-b").click(function () {
    window.location.href = "/admin/project/project";
});



































