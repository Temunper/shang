$("#add-adp").click(function () {
    var fd = new FormData();
    var file_obj = document.getElementById('file').files[0];

    fd.append('name', $("#name").val());
    fd.append('theme_id', $("#theme option:selected").val());
    fd.append('file_path', $("#file_path").val());
    fd.append('file', file_obj);

    $.ajax({
        url: "/admin/ad/add",
        dataType: "json",
        type: "POST",
        processData: false,
        contentType: false,
        mimeType: "multipart/form-data",
        data: fd,
        success: function (data) {
            data = JSON.parse(data);
            alert(data.data);
        }
    })
});

$("body").on("change", "#theme", function () {
    var theme_id = $("#theme option:selected").val();
    var d_ad = [{}];
    d_ad[0] = {
        id:0,
        text:null
    };
    for (i = 1; i < ad.length+1; i++) {
        if (ad[i].theme_id == theme_id){
            d_ad[i] = {
                id:ad[i].ad_id,
                text:ad[i].name
            }
        }
    }
$("#ad").select2({
    theme: "default",
    placeholder: {
        text: "",
    },
    data: d_ad,
})

});
$(function () {
    var d_project = [{}];
    d_project[0] = {
        id: 0,
        text:null
    };
    for (i = 1; i < project.length+1; i++) {
        d_project[i] = {
            id: project[i].project_id,
            text: project[i].name
        }
    }
    console.log(d_project);
    $("#project").select2({
        theme: "default",
        placeholder: {
            text: "",
        },
        data: d_project,
    })
});