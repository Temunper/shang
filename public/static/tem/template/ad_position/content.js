$("#update-adp").click(function () {
    var fd = new FormData();
    var file_obj = document.getElementById('file').files[0];

    fd.append('ad_position_id',$(this).attr("data-adp"));
    fd.append('name', $("#name").val());
    fd.append('ad_id', $("#ad option:selected").val());
    fd.append('project_id', $("#project option:selected").val());
    fd.append('file_path', $("#file_path").val());
    fd.append('file', file_obj);
    fd.append('sort', $("#sort").val());
    fd.append('click_num', $("#click_num").val());
    fd.append('attention', $("#attention").val());

    $.ajax({
        url: "/admin/ad_position/update",
        dataType: "json",
        type: "POST",
        processData: false,
        contentType: false,
        mimeType: "multipart/form-data",
        data: fd,
        success: function (data) {
            data = JSON.parse(data);
            alert(data.data);
            window.location.reload();
        }
    })
});


$("body").on("change", "#theme", function (e, data = null) {
    $("#ad").empty();
    var theme_id = $("#theme option:selected").val();
    var d_ad = [{}];
    if (data)
        d_ad[0] = {
            id: data.id,
            text: data.text
        }; else d_ad[0] = {
        id: 0,
        text: null
    };
    for (i = 1; i < ad.length + 1; i++) {
        if (ad[i - 1].theme_id == theme_id) {
            d_ad[i] = {
                id: ad[i - 1].ad_id,
                text: ad[i - 1].name
            }
        }
    }
    $("#ad").select2({
        theme: "default",
        placeholder: {
            text: "",
        },
        data: d_ad,
    });
});


$(function () {
    var d_project = [{}];
    d_project[0] = {
        id: 0,
        text: null
    };
    for (i = 1; i < project.length + 1; i++) {
        d_project[i] = {
            id: project[i - 1].project_id,
            text: project[i - 1].name
        }
    }
    $("#project").select2({
        theme: "default",
        placeholder: {
            text: "",
        },
        data: d_project,
    });
    $("#project").select2('val', $("#project").attr("data-project"));   //渲染下拉框
    for (i = 0; i < ad.length; i++) {
        if (ad[i].ad_id == $("#ad").attr("data-ad")) {
            $("#theme").val(ad[i].theme_id).trigger("change", data = {
                id: ad[i].ad_id,
                text: ad[i].name
            })
        }
    }
});
$("#go-back").click(function () {
    window.location.href = "/admin/ad_position/ad_position";
});