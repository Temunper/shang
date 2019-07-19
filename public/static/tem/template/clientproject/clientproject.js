var value = $(".set-customer").attr('data-client');
var key = $(".set-project").attr('data-project');
value = JSON.parse(value);
key = JSON.parse(key);

//初始化选择框
$(function () {
    var client_name = [{}];
    var project = [{}];
    for (i = 0; i < value.length + 1; i++) {
        if (i === 0) client_name[i] = '----------------';
        else client_name[i] = value[i - 1].name;
    }
    for (i = 0; i < key.length + 1; i++) {
        if (i === 0) project[i] = '----------------';
        else project[i] = key[i - 1].name;
    }
    $(".set-customer-btn").select2({
        theme: "classic",
        placeholder: {
            text: "",
        },
        data: client_name,
    });
    $(".set-project-btn").select2({
        theme: "classic",
        placeholder: {
            text: "",
        },
        data: project,
    });
});


//绑定
$(".confirm-cp").click(function () {
    var client_name = $(".set-customer-btn").select2('data')[0];
    var project_name = $(".set-project-btn").select2('data')[0];
    if (client_name && project_name && client_name.id !== "----------------" && project_name.id !== "----------------") {
        for (i in value) {
            if (value[i].name === client_name.id) {
                var client_id = value[i].client_id;
            }
        }
        for (i in key) {
            if (key[i].name === project_name.id) {
                var project_id = key[i].project_id;
            }
        }
    } else {
        alert("请进行选择")
        return false
    }
    $.ajax({
        url: "/admin/clientproject/add",
        data: {
            'client_id': client_id,
            'project_id': project_id
        },
        dataType: 'json',
        type: 'POST',
        success: function (data) {
            alert(data.data);
        }
    })
});