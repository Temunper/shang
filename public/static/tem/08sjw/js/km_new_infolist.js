$(function(){
	$(".nav li").on("click",function(){
		$(this).addClass("active").siblings().removeClass("active")
	}),

	$(".words-limit").each(function(){
		var s=$(this).text().length;s>90?$(this).text($(this).text().substr(0,90)+"..."):$(this).text($(this).text().substr(0,90))
	}),

	$(".div-tabs a ").hover(function(){
		$(this).siblings().removeClass("link-active"),
		$(".content-tab").eq($(this).index()).removeClass("hidden").siblings().addClass("hidden")
	})



	//右边切换
	$(".new_bd .title1").find("a:eq(0)").hover(function(){
		$(this).parent().siblings(".new").show().siblings("ul").hide();
		$(this).addClass("live").siblings().removeClass("live");
	});
	$(".new_bd .title1").find("a:eq(1)").hover(function(){
		$(this).parent().siblings(".hot").show().siblings("ul").hide();
		$(this).addClass("live").siblings().removeClass("live");
	});
	$(".new_bd .title1").find("a:eq(2)").hover(function(){
		$(this).parent().siblings(".rec").show().siblings("ul").hide();
		$(this).addClass("live").siblings().removeClass("live");
	});



	$(".item4boxcontent .contentlist").eq(0).show();
	$(".item4boxtitle ul li").mouseover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(".item4boxcontent .contentlist").eq($(this).index()).show().siblings().hide()
	})
	$(".hotzixun .contentlist").eq(0).show()
	$(".hotzhuanti .contentlist").eq(0).show()
	$(".hotzhuanti .title li").hover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(this).closest(".hotzhuanti").find(".contentlist").hide();
		$(this).closest(".hotzhuanti").find(".contentlist").eq($(this).index()).show()
	},function(){})
	$(".hotzixun .title li").hover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(this).closest(".hotzixun").find(".contentlist").hide();
		$(this).closest(".hotzixun").find(".contentlist").eq($(this).index()).show()
	},function(){})

	


	//搜索功能
	// $("#titleselect .inputbox .selectboxdown1 ul li").on("click",function(){
	// 	$("#titleselect .inputbox .selectdropdown p").text($(this).text())
	// 	$("#titleselect .inputbox").find(".selectboxdown1").slideUp()
	// })
	// $("#titleselect .inputbox .selectdropdown .icon1").show()
	// $("#titleselect .inputbox .selectdropdown").on("click",function(){
	// 	if($(this).next().is(":visible")){
	// 		$(this).next().stop().slideUp()
	// 	}else{
	// 		$(this).next().hide().stop().slideDown()
	// 		$(this).find(".icon2").show()
	// 		$(this).find(".icon1").hide()
	// 	}
	// 	if($(this).next().next().is(":visible")){
	// 		$(this).next().next().slideUp()
	// 	}
	// })
	
	// $("#titleselect .inputbox input").on("click",function(){
	// 	if($(this).parent().find(".selectboxdown1").is(":visible")){
	// 		$(this).parent().find(".selectboxdown1").stop().slideUp()
	// 	}
	// 	if($(this).parent().find(".selectboxdown2").is(":visible")){
	// 		$(this).parent().find(".selectboxdown2").stop().slideUp()
	// 	}else{
	// 		$(this).parent().find(".selectboxdown2").slideDown()
	// 	}
	// })
	// $(".inputbox").hover(function(){},function(){
	// 	$(this).find(".icon2").hide()
	// 	$(this).find(".icon1").show()
	// 	$(this).find(".selectboxdown1").slideUp()
	// 	$(this).find(".selectboxdown2").slideUp()
	// })
	
	// $("#titleselect .inputbox .selectboxdown2 .selectlist").on("click",function(){
	// 	$(".inputbox input").val($(this).find("p").text())
	// 	$(this).parent().slideUp()
	// 	$(".inputbox input").focus()
	// })
});

// nav 滚动
var s = $(".nav-ctn").offset().top;
var t = $(".page-zhome-main").offset().top;
var nh = $("#nav").outerHeight()
$(window).scroll(function(){
	$(window).scrollTop()>=$("#nav").offset().top&&$("#nav").addClass("nav-fixed"),
	$(window).scrollTop()<s&&$("#nav").removeClass("nav-fixed"),
 	$(window).scrollTop()>= t - nh &&$("#nav").removeClass("nav-fixed").addClass("nav-absolute"),
 	$(window).scrollTop()< t - nh &&$("#nav").removeClass('nav-absolute');
});
