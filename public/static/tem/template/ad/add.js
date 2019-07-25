$("#add-ad").click(function () {
    var fd = new FormData();                                                //实例化表单
    var file_obj = document.getElementById('file').files[0];     //获取文件

    fd.append('name', $("#name").val());
    fd.append('theme_id', $("#theme option:selected").val());
    fd.append('file_path', $("#file_path").val());
    fd.append('file',file_obj);

    $.ajax({                            //异步请求
        url: "/admin/ad/add",
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
   window.location.href = "/admin/ad/ad"
});