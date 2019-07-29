$(".s_money").on('click', function () {

    s_money = $(this).attr('data-money');
    window.location.href = "/slist/f/f/" + s_money;
});

$("#search").on('click', function () {

    clas = $("#searchCatalogInnerCode").val();
    pro_name = $("#selectinput").val();
    if (pro_name == "") {
        alert('请输入要查询的项目名');
        return;
    }
    if (clas == "") {
        window.location.href = "/slist/f/f/f/" + pro_name;
    } else {
        window.location.href =  "/slist/"+clas+"/f/f/" + s_money;
    }
})
