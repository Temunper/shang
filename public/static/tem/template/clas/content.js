$("#update-class").click(function () {
    var fd = new FormData();
    var file_obj = document.getElementById('file').files[0];
    fd.append('class_id',$(".data-class").attr("data-clas"));
    fd.append('name', $("#name").val());
    fd.append('sort', $("#sort").val());
    fd.append('describe', $("#description").val());
    fd.append('f_class_id', $("#f_class_id option:selected").val());
    fd.append('file_path', $("#file_path").val());
    fd.append('file',file_obj);
    fd.append('keywords',$("#keywords").val());

    $.ajax({
        url: "/admin/clas/update",
        dataType: "json",
        type:"POST",
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

$("#go-back").click(function () {
    window.location.href = "/admin/clas/clas";
});

