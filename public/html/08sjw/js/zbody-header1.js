$(function(){
    function hideAllPopupExcept(form, s){
        if (s != "industry") {
            form.find(".div-select-industry").slideUp();
        }
        if (s != "invest") {
            form.find(".div-select-invest").slideUp();
        }
        if (s != "keyword") {
            form.find(".div-select-keyword").slideUp();
        }
    }
    
    function main(){
        //选择行业弹窗
        var form = $("#form-header1-search");
        form.find(".field-industry,.div-anchor-1").click(function(){
            hideAllPopupExcept(form, "industry");
            form.find(".div-select-industry").stop(true,false).slideToggle();
        });
        form.find(".div-select-industry .link").click(function(){
            var text = $(this).text();
            form.find(".field-industry").val(text);
            $("#searchCatalogInnerCode").val($(this).attr("data-id"));
            form.find(".div-select-industry").slideUp();
            return false;
        });
        //选择投资金额弹窗
        form.find(".field-invest,.div-anchor-2").click(function(){
            hideAllPopupExcept(form, "invest");
            form.find(".div-select-invest").stop(true,false).slideToggle();
        });
        form.find(".div-select-invest .link").click(function(){
            var text = $(this).text();
            form.find(".field-invest").val(text);
            $("#searchInvestMoney").val($(this).attr("data-id"));
            form.find(".div-select-invest").slideUp();
            return false;
        });
        //推荐菜单
        form.find(".field-keyword").click(function(){
            hideAllPopupExcept(form, "keyword");
            form.find(".div-select-keyword").stop(true,false).slideToggle();
        });
        
        //移开区域时隐藏弹窗
        $(".page-header").hover(function(){
        }, function(){
            hideAllPopupExcept(form, null);
        });
       		//鼠标移出隐藏--new 头部
		    $(".form-search").hover(function () {
		    }, function () {
		        $(".div-select-industry,.div-select-keyword").slideUp(300);
		    });
        
    }
    
    main();
});
