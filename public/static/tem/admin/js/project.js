$(function(){
    // 全选
    $(".selectAll").on('click', function() {
        if($(".selectAll").prop("checked")){
            $(".checkboxone").prop("checked","checked");
        }else{
            $(".selectAll").removeAttr("checked");
            $(".checkboxone").removeAttr("checked");
        }
    })
    //删除
    $(".operate.del").on('click', function () {
        $(this).closest('tr').remove();
    });
})