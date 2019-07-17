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
    window.location.href = "/admin/project/project?class_id=" + class_id+"&project_name="+project_name+"&client_name="+client_name;
});
