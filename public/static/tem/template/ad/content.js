$("#update-ad").click(function () {
    var fd = new FormData();
    var file_obj = document.getElementById('file').files[0];

    fd.append('ad_id',$(this).attr("data-ad"));
    fd.append('name', $("#name").val());
    fd.append('theme_id', $("#theme option:selected").val());
    fd.append('file_path', $("#file_path").val());
    fd.append('file',file_obj);

    $.ajax({
        url: "/admin/ad/update",
        dataType: "json",
        type:"POST",
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
$(function () {                     //渲染
    var theme_id = $("#theme").attr("data-theme");
    $("#theme").val(theme_id);
});

$("#go-back").click(function () {
    window.location.href = "/admin/ad/ad"
});