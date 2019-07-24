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
    window.open("/admin/ad_position/plus");
});
$(".update-adp").click(function () {
    console.log(123);
    window.open("/admin/ad_position/content?ad_position_id=" + $(this).attr("data-adp"));
});

$("body").on("change", "#select-theme", function () {
    $("#ad").empty();
    var theme_id = $("#select-theme option:selected").val();
    var d_ad = [{}];
    d_ad[0] = {
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
    $("#select-ad").select2({
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
        text: null
    };
    for (i = 1; i < project.length + 1; i++) {
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
    })
});

//提交筛选
$(".set-adp-btn").click(function () {
    var theme_id = $("#select-theme option:selected").val();
    var ad_id = $("#select-ad option:selected").val();
    if (ad_id === undefined) ad_id = 0;
    var project_id = $("#select-project option:selected").val();
    var sort = $("#select-sort option:selected").val();
    console.log(sort);
    window.location.href = "/admin/ad_position/ad_position?theme_id=" + theme_id + "&ad_id=" + ad_id + "&project_id=" + project_id + "&sort=" + sort;
});

