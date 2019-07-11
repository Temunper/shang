$(function () {
    //$(".pub-info").find("a").attr("data-tabid", 1)
    //选项卡
    $(".nav li").on("click", function () {
        //$(".nav li").removeClass("active");
        $(this).addClass("active").siblings().removeClass("active");
    })


//分页按钮
//    $(".pagenav a").on("click", function () {
//        //console.log($(this).index())
//        $(this).addClass("active").siblings().removeClass("active");
//        $(this).index() != 1 ? $(".pagenav a").eq(0).show() : $(".pagenav a").eq(0).hide()
//        $.get("//www.kmway.com/cms/advertise/hitcount", {
//            ADID: 1
//        }, function (r) {
//            console.log(r)
//        });
//    });
//回到顶部

    //$(window).on("scroll", function () {
    //    //console.log($("#top").offset().top)
    //    var scTop = $(this).scrollTop();
    //    var h1=$(".hot-tag").offset().top;
    //    var winHeight = $(window).height();
    //    if(scTop>h1-winHeight/2){
    //        $("#top").fadeIn()
    //    }else{
    //        $("#top").fadeOut()
    //    }
    //})
    //$("#top").on("click",function(){
    //    $("html,body").animate({"scrollTop" : -$(this).offset().top}, 1000)
    //
    //});



    //控制字数
    $(".words-limit").each(function(){
        var length=$(this).text().length;
        if(length>60){
            $(this).text($(this).text().substr(0,60)+"...")
        }else{
            $(this).text($(this).text().substr(0,60))
        }
    })

//广告列表选项卡
    $(".div-tabs a ").hover(function () {
        $(this).siblings().removeClass("link-active");
        $(".content-tab").eq($(this).index()).removeClass("hidden").siblings().addClass("hidden");
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



    //底部hover切换
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
    //     $("#titleselect .inputbox .selectdropdown p").text($(this).text())
    //     $("#titleselect .inputbox").find(".selectboxdown1").slideUp()
    // })
    // $("#titleselect .inputbox .selectdropdown .icon1").show()
    // $("#titleselect .inputbox .selectdropdown").on("click",function(){
    //     if($(this).next().is(":visible")){
    //         $(this).next().stop().slideUp()
    //     }else{
    //         $(this).next().hide().stop().slideDown()
    //         $(this).find(".icon2").show()
    //         $(this).find(".icon1").hide()
    //     }
    //     if($(this).next().next().is(":visible")){
    //         $(this).next().next().slideUp()
    //     }
    // })
    
    // $("#titleselect .inputbox input").on("click",function(){
    //     if($(this).parent().find(".selectboxdown1").is(":visible")){
    //         $(this).parent().find(".selectboxdown1").stop().slideUp()
    //     }
    //     if($(this).parent().find(".selectboxdown2").is(":visible")){
    //         $(this).parent().find(".selectboxdown2").stop().slideUp()
    //     }else{
    //         $(this).parent().find(".selectboxdown2").slideDown()
    //     }
    // })
    // $(".inputbox").hover(function(){},function(){
    //     $(this).find(".icon2").hide()
    //     $(this).find(".icon1").show()
    //     $(this).find(".selectboxdown1").slideUp()
    //     $(this).find(".selectboxdown2").slideUp()
    // })
    
    // $("#titleselect .inputbox .selectboxdown2 .selectlist").on("click",function(){
    //     $(".inputbox input").val($(this).find("p").text())
    //     $(this).parent().slideUp()
    //     $(".inputbox input").focus()
    // })

});



//$(function () {
//  //滚动加载更多
//  var isloading = false;
//  $(window).on("scroll", function () {
//      //console.log($(".scroll-more").hasClass("no-more"))
//      var scTop = $(this).scrollTop();
//      var _top = $(".scroll-more").offset().top;
//      var winHeight = $(window).height();
//
//      if (scTop >= (_top - winHeight ) && isloading == false) {
//          if (!$(".scroll-more").hasClass("no-more")) {
//              $(".scroll-more").html("数据加载中...");
//              httpGetMore($(".scroll-more"));
//          }
//          function showDataList(arr, moreEl) {
//              var html = '';
//              for (var i = 0; i < arr.length; i++) {
//                  var o = arr[i];
//                  //o.link = o.link ? o.link.replace(resourceprefix, prefix) : "";
//                  var source = o.source ? o.source : "互联网";
//                  var editor = o.editor ? o.editor : "小A";
//                  var publishDate = new Date(o.publishdate).format("yyyy-MM-dd");
//                  var summary=wordsLimit(o.summary);
//                  //console.log(time)
//                  html += '<div class="pub-info-list">';
//                  html += '   <a href="' + o.link + '" ><img src="'+prefix + o.logofile + '" class="l"></a>';
//                  html += '   <a href="' + o.link + '"><h3 class="title">' + o.title + '</h3></a>';
//                  html += '   <div class="publish-time">发布时间：' + publishDate + '&nbsp;&nbsp; <span>编辑：' + editor + '</span></div>';
//                  html += '   <p> <span class="words-limit"> ' + summary + '</span> <a href="' + o.link + '">查看详情>></a> </p>';
//                  html += '   <div class="publish-from">来源：' + source + ' | 浏览：<span>' + o.hitcount + '</span></div>';
//                  html += '</div>';
//              }
//              $(moreEl).before(html);
//          }
//
//          function httpGetMore(moreEl) {
//              isloading = true;
//              var catalogId = parseFloat($(moreEl).attr("data-catalogid"));
//              var pageIndex = parseFloat($(moreEl).attr("data-pageindex"));
//              var pageSize = parseFloat($(moreEl).attr("data-pagesize"));
//              //console.log("加载数据")
//
//              $.ajax({
//                  url: prefix + "api/jiameng/contentdata",
//                  type: "GET",
//                  data: {
//                      catalogid: catalogId,
//                      //catalogid: 17210,
//                      level: "CurrentAndChild",
//                      siteid: siteId,
//                      ordertype: "Recent",
//                      loadcontent: true,
//                      contenttype: "Article",
//                      pageindex: pageIndex,
//                      pagesize: pageSize,
//                      columns: "id,link,logofile,title,subtitle,publishDate,hitCount,summary,editor,source"
//                  },
//                  dataType: "json",
//                  success: function (data) {
//                      //console.log("数据")
//                      $(moreEl).attr("data-pageindex", pageIndex + 1);
//                      var arr = data._RESULT;
//                      showDataList(arr, moreEl);
//                      $(".scroll-more").html("鼠标滚动展示更多↓");
//                      isloading = false;
//                      if (data.pagesize * (data.pageindex + 1) >= data.total) {
//                          $(".scroll-more").html("没有更多数据了");
//                          $(".scroll-more").addClass("no-more")
//
//                      }
//                  },
//                  error: function (XMLHttpRequest, textStatus, errorThrown) {
//                      //alert(XMLHttpRequest.status);
//                      //alert(XMLHttpRequest.readyState);
//                      //alert(textStatus);
//                      $(".scroll-more").html("没有更多数据了");
//                  },
//                  complete: function () {
//                  }
//              });
//          };
//
//          //控制字数
//          function wordsLimit(str){
//              if(str.length&&str.length>60){
//                  return str.substr(0,60)+"..."
//              }else{
//                  return str.substr(0,60)
//              }
//          }
//
//
//      }
//
//  });
//
//})


//时间
Date.prototype.format = function(format){
    var o = {
        "M+" : this.getMonth()+1, 
        "d+" : this.getDate(), 
        "h+" : this.getHours(), 
        "m+" : this.getMinutes(), 
        "s+" : this.getSeconds(), 
        "q+" : Math.floor((this.getMonth()+3)/3), //quarter
        "S" : this.getMilliseconds() //millisecond
    }

    if(/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }

    for(var k in o) {
        if(new RegExp("("+ k +")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length));
        }
    }
    return format;
}
// $(function () {
//     //固定导航栏
//     //window.onload=function(){
//     var nav_h_init = $(".fixdbox").offset().top;
//     var fix_h =$(".fixdbox").height();
//     var last_h = $("#fixed_2").offset().top;
// 	var text_h =$("#fixed_2").height();
// //	var fix_h =$(".fixdbox").height();
// 	var ad_h = $(".div-zhome-kmway-highlight").offset().top;
//     if((ad_h - last_h - text_h ) >  50){
//     	$(".fixdbox").removeClass("fixdon");
//     }
//     else{
//     	$(window).scroll(function () {
// 	        var nav_h = $(".fixdbox").offset().top;
// 		    var ad_h = $(".div-zhome-kmway-highlight").offset().top;
		    
// 	//	    alert(fix_h)
// 	//	    console.log(ad_h);
// 	//	    console.log(nav_h);
// 	//	    console.log(ad_h - nav_h);
// 	        if ($(window).scrollTop() >= $(".fixdbox").offset().top) {
// 	            $(".fixdbox").addClass("fixdon")
// 	//          $(".fixdbox").show();
// 	        }
// 	        if ($(window).scrollTop() < nav_h_init) {
// 	            $(".fixdbox").removeClass("fixdon");
// 	        }
// 	        if(ad_h - nav_h > fix_h){
// 	        	$(".fixdbox").show();
// 	        }
// 	        if(ad_h - nav_h < fix_h){
// 	        	$(".fixdbox").removeClass("fixdon");
// 	        	$(".fixdbox").hide()
// 	        }
//     	})
//     }
// })



