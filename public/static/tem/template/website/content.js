$(function () {
    var theme_id = $("#name").attr("data-name");
    var type = $("#type").attr("data-type");
    $("#name").val(theme_id);
    $("#type").val(type);
});
$("#update-website").click(function () {
    var fd = new FormData();
    var file_obj = document.getElementById('file').files[0];

    fd.append('website_id',$("#domain").attr("data-website"));
    fd.append('domain', $("#domain").val());
    fd.append('theme_id', $("#name option:selected").val());
    fd.append('filing_number', $("#filing_number").val());
    fd.append('type', $("#type option:selected").val());
    fd.append('file_path', $("#file_path").val());
    fd.append('file', file_obj);
    fd.append('company_name', $("#company_name").val());
    fd.append('company_addr', $("#company_addr").val());
    fd.append('phone', $("#phone").val());
    fd.append('keywords',$("#keywords").val());
    fd.append('title', $("#title").val());
    fd.append('description',$("#description").val());
    $.ajax({
        url: "/admin/website/update",
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
$("#go-back").click(function () {
    window.location.href = "/admin/website/website";
});