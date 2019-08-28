$(".del-adp").click(function () {
    var adp_id = $(this).attr("data-adp");
    var r = confirm("是否删广告位？");
    var del = $(this);
    if (r === true) {
        $.ajax({
            url: "/admin/ad_position/update_status",
            data: {
                ad_position_id: adp_id
            },
            dataType: "json",
            type: "POST",
            success: function (data) {
                data = JSON.parse(data);
                if (data.code === 200) {
                    del.parent().parent().html("");
                }
                alert(data.data);
            }
        })
    }
});
$(".add-adp").click(function () {
    window.location.href = "/admin/ad_position/plus";
});
$(".update-adp").click(function () {
    window.location.href = "/admin/ad_position/content?ad_position_id=" + $(this).attr("data-adp");
});


$("body").on("change", "#select-theme", function () {
    $("#select-ad").empty();
    var theme_id = $("#select-theme option:selected").val();
    var d_ad = [{}];
    d_ad[0] = {
        id: 0,
        text: "--------"
    };
    for (i = 1; i < ad.length + 1; i++) {                   //拼接数据
        if (ad[i - 1].theme_id == theme_id) {
            if (search.ad_id == ad[i - 1].ad_id)
                d_ad[0] = {
                    id: ad[i - 1].ad_id,
                    text: ad[i - 1].name
                };
            else
                d_ad[i] = {
                    id: ad[i - 1].ad_id,
                    text: ad[i - 1].name
                }
        }
    }
    search.ad_id = 0;
    $("#select-ad").select2({           //渲染下拉框
        theme: "default",
        placeholder: {
            text: "",
        },
        data: d_ad,
    })

});

//  查询后渲染
$(function () {
    var d_project = [{}];
    // console.log(project);
    d_project[0] = {id: 0, text: "----------"};
    for (i = 1; i < project.length + 1; i++) {
        if (project[i - 1].project_id == search.project_id){
            d_project[0] = {
                id: project[i - 1].project_id,
                text: project[i - 1].name
            }
        } else
            d_project[i] = {
                id: project[i - 1].project_id,
                text: project[i - 1].name
            }
    }
    $("#select-project").select2({
        theme: "default",
        placeholder: {
            text: "",
        },
        data: d_project,
    });
    // $("#select-project").select2("val","31());
    $("#select-theme").val(search.theme_id).trigger("change");
    // $("#select-ad").select2('val',search.ad_id)
});

//提交筛选
$(".set-adp-btn").click(function () {
    var theme_id = $("#select-theme option:selected").val();    //获取相关值
    var ad_id = $("#select-ad option:selected").val();
    if (ad_id === undefined) ad_id = 0;                         //如果ad_id的dom元素位定义，返回变量值为0
    var project_id = $("#select-project option:selected").val();
    var sort = $("#select-sort option:selected").val();
    window.location.href = "/admin/ad_position/ad_position?theme_id=" + theme_id + "&ad_id=" + ad_id + "&project_id=" + project_id + "&sort=" + sort;
});

