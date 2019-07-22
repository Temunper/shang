$(function () {
    initProvince("d_province");
    var class_id = $(".class-data").attr("data-class");
    var f_class_id = null;
    var area = $(".area-data").attr("data-place");
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
    $("#d_money").val(data.money);
});

$("#d_province").bind('change', function () {
    var proid = $("#d_province option:selected").val()
    onProvinceChange(proid, "d_city")
});
$("#d_city").bind('change', function () {
    var city = $("#d_city option:selected").val()
    onCityChange(city, "d_country")
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

