//  更改名字
$('.set-right').click(function () {
    var input = $('.hidden-input')
    if (input.css('visibility') === 'hidden') {
        input.css('visibility', 'visible')
    } else {
        input.css('visibility', 'hidden')
        input.find('.hidden-input-text').val('')
    }
});
//  提交更改名字
$(".confirm").click(function () {
    var name = $(".hidden-input-text").val()
    if (name) {
        $.ajax({
            url: "/admin/admin/update",
            data: {
                "name": name,
            },
            dataType: "json",
            type: "GET",
            success: function (data) {
                alert(data.data)
            }
        })
    } else {
        console.log('用户名不为空')
    }
});

//注销账户
$(".del-admin").click(function () {
    window.location.href = "/admin/admin/update_status?status=删除"
});

//修改密码
$(".confirm-pass").click(function () {
    var old_pass = $(".old-pass").val()
    var new_pass = $(".new-pass").val()
    $.ajax({
        url: "/admin/admin/update_pass",
        data:{
            "old_pass":old_pass,
            "new_pass":new_pass
        },
        dataType: "json",
        type:"POST",
        success:function (data) {
            alert(data.data)
        }
    })
});


















