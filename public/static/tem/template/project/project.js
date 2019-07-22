//地区三级联动
$(function () {
    initProvince("province");
});
$("#province").bind('change', function () {
    var proid = $("#province option:selected").val()
    onProvinceChange(proid, "city")
});
$("#city").bind('change', function () {
    var city = $("#city option:selected").val()
    onCityChange(city, "country")
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
                "400_num": $("#400").val(),
                company_name: $("#company_name").val(),
                company_addr: $("#company_addr").val(),
                superiority: $("#superiority").val(),
                analysis: $("#analysis").val(),
                prospect: $("#prospect").val(),
                summary: $("#summary").val(),
                contact: $("#contact").val(),
                class_id: class_id,
                money: $("#money").val(),
                keywords: $("#keywords").val(),
                description: $("#description").val(),
                kf_type: $("#kf_type").val()
            },
            client_name:$("#client_name").val()
        },
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            data = jQuery.parseJSON(data);
            alert(data.data);
        }
    })

});

//取消
$(".submit-b").click(function () {
   $(".add-page").css('visibility','hidden');
   $(".list-page").css('filter','blur(0px)')
});
//添加按钮
$(".add-project").click(function () {
    $(".add-page").css('visibility','visible');
    $(".list-page").css('filter','blur(2px)')
});