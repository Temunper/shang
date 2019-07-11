$(function(){
    //鼠标经过菜单，改变图标；弹出二级菜单
    function main(){
        $("#zhome-ul-menu>li").hover(function(){
            $(this).addClass("li-hover");
            $(this).find(">.link>.icon").addClass("icon-ic-nav-arrow-white").removeClass("icon-ic-nav-arrow-grey");
//          $("#zhome-ul-menu .ul-submenu").stop().fadeOut(200);
            $("#zhome-ul-menu .ul-submenu").stop().hide();
            $("#zhome-ul-menu .ul-submenu").css("opacity",'0');
            var submenuEl = $(this).find(".ul-submenu");
            if (submenuEl) {
//              submenuEl.stop().fadeIn(200);
                submenuEl.stop().show();
                submenuEl.css("opacity",'1');
            }
        }, function(){
            $(this).removeClass("li-hover");
            $(this).find(">.link>.icon").addClass("icon-ic-nav-arrow-grey").removeClass("icon-ic-nav-arrow-white");
//          $("#zhome-ul-menu .ul-submenu").stop().fadeOut(200);
            $("#zhome-ul-menu .ul-submenu").stop().hide();
            $("#zhome-ul-menu .ul-submenu").css("opacity",'0');
        });
    }
    
    main();
    
});
$(function(){
    //slideshow
    var el = null;
    var elSize = {
        width: 0,
        height: 0
    };
    var idx = 0;
    var length = 0;
    var timerId = null;
    //动画方向：
    //POS_LTR = 从左到右
    //POS_TTB = 从上到下
    var POS_LTR = 0, POS_TTB = 1;
    var position = POS_LTR;
    
    function slideTo(toIdx){
        idx = toIdx;
        if (position == POS_LTR) {
            el.find(".slideshow-images .images-inner").animate({
                left: (-elSize.width * idx) + "px"
            });
        } else if (position == POS_TTB) {
            el.find(".slideshow-images .images-inner").animate({
                top: (-elSize.height * idx) + "px"
            });
        }
        el.find(".slideshow-dots .dots-inner .icon").eq(idx).addClass("icon-slide-dot-active").removeClass("icon-slide-dot-normal").siblings().addClass("icon-slide-dot-normal").removeClass("icon-slide-dot-active");
        startTimer();
    }
    
    function stopTimer(){
        if (timerId) {
            window.clearTimeout(timerId);
            timerId = null;
        }
    }
    
    function startTimer(){
        stopTimer();
        timerId = window.setTimeout(function(){
            var toIdx = idx + 1;
            if (toIdx > length - 1) {
                toIdx = 0;
            }
            slideTo(toIdx);
        }, 5000);
    }
    
    function initDotsHtml(){
        var html = '';
        for (var i = 0; i < length; i++) {
            html += '<div class="icon ' + (i === 0 ? 'icon-slide-dot-active' : 'icon-slide-dot-normal') + '" data-idx="' + i + '"></div>';
        }
        el.find(".slideshow-dots .dots-inner").html(html);
        
        el.find(".slideshow-dots .dots-inner .icon").click(function(){
            var toIdx = parseFloat($(this).attr("data-idx"));
            slideTo(toIdx);
        });
    }
    
    function main(){
        el = $("#zhome-slideshow");
        elSize.width = el.width();
        elSize.height = el.height();
        length = el.find(".slideshow-images .images-inner .link").length;
        
        if (position == POS_LTR) {
            el.find(".slideshow-images .images-inner").width(elSize.width * length).height(elSize.height);
        } else if (position == POS_TTB) {
            el.find(".slideshow-images .images-inner").width(elSize.width).height(elSize.height * length);
        }
        
        initDotsHtml();
        
        startTimer();
    }
    
    main();
    
});

$(function(){
//	$(".item4boxcontent .contentlist").eq(0).show();
	$(".item4boxtitle ul li").mouseover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(".item4boxcontent .contentlist").eq($(this).index()).show().siblings().hide()
	})
})


$(function(){
    //clicktab
    function main(){
        $("#div-zhome-list-text .div-title .link-tab").click(function(){
            var idx = $(this).index();
            $(this).addClass("link-active").siblings().removeClass("link-active");
            $(this).parent().next().find(".content-tab").eq(idx).removeClass("hidden").siblings().addClass("hidden");
            return false;
        });
    }
    
    main();
});
$(function(){
    //【精品专区】鼠标经过效果
    function main(){
//      $("#div-zhome-jpzq .div-body .div-item").hover(function(){
//          $(this).find(".icon").addClass("icon-btn-detail-hover").removeClass("icon-btn-detail-normal");
//      }, function(){
//          $(this).find(".icon").addClass("icon-btn-detail-normal").removeClass("icon-btn-detail-hover");
//      });
		var divTextHeight = $(".div-zhome-xpsx .div-body .div-item .div-text").eq(0).outerHeight();
        $(".div-zhome-xpsx .div-body .div-item").hover(function(){
            $(this).find(".div-text").stop().animate({
                bottom: 0
            },0);
        }, function(){
            $(this).find(".div-text").stop().animate({
                bottom: (-divTextHeight) + "px"
            },0);
        });
        
        
        $(".div-zhome-xpsx .div-body .ul-sqr li").hover(function(){
        	$(this).css("top","-5px");
        	$(this).css("box-shadow","0 3px 8px rgba(0,0,0,0.2)");
        },function(){
        	$(this).css("top","0");
        	$(this).css("box-shadow","none");
        })
        
    }
    
    main();
});

$(function(){
    //【新品上线】鼠标经过效果
    function main(){
        var divTextHeight = $("#div-zhome-xpsx .div-body .div-item .div-text").eq(0).outerHeight();
        $("#div-zhome-xpsx .div-body .div-item").hover(function(){
            $(this).find(".div-text").stop().animate({
                bottom: 0
            });
        }, function(){
            $(this).find(".div-text").stop().animate({
                bottom: (-divTextHeight) + "px"
            });
        });
    }
    
    main();
});

$(function(){

    function main(){
        //【品牌推荐】广播幻灯片
        var marqueeListEl = $("#div-zhome-pptj .div-title .div-marquee .div-list .div-inner");
        var offsetY = 0;
        var itemHeight = 40;
        var totalHeight = marqueeListEl.height();
        if (totalHeight > itemHeight) {
            window.setInterval(function(){
                if (offsetY < totalHeight - itemHeight) {
                    offsetY += itemHeight;
                } else {
                    offsetY = 0;
                }
                marqueeListEl.animate({
                    top: (-offsetY) + "px"
                });
            }, 5000);
        }
        
        //【品牌推荐】鼠标经过效果
        var divTextHeight = $("#div-zhome-pptj .div-body .div-item .div-text").eq(0).outerHeight();
        $("#div-zhome-pptj .div-body .div-item").hover(function(){
            $(this).find(".div-text").stop().animate({
                bottom: 0
            },0);
        }, function(){
            $(this).find(".div-text").stop().animate({
                bottom: (-divTextHeight) + "px"
            },0);
        });
        
        $(".div-zhome-pptj .div-body .ul-sqr li").hover(function(){
        	$(this).css("top","-5px");
        	$(this).css("box-shadow","0 3px 8px rgba(0,0,0,0.2)");
        },function(){
        	$(this).css("top","0");
        	$(this).css("box-shadow","none");
        })
        
    }
    
    main();
});


$(function(){
    //【轮博下方】Tab鼠标经过效果
    function main(s){
        $(s).hover(function(){
        	$(this).find('i').css('left','66px')
        }, function(){
        	$(this).find('i').css('left','60px')
        });
    }
    
    main(".icon-bg-block-1");
    main(".icon-bg-block-2");
});

$(function(){
    //【热门项目】Tab鼠标经过效果
    function main(){
        var divTextHeight = $(".div-zhome-jmzx .div-col-2x5 ul li").eq(0).outerHeight();
        $(".div-zhome-jmzx .div-col-2x5 ul li").hover(function(){
        	$(this).css("box-shadow","0 5px 10px rgba(0,0,0,0.1)");
            $(this).stop().animate({
                top: -5+'px'
            },0);
        }, function(){
        	$(this).css("box-shadow","none");
            $(this).stop().animate({
                top: 0 + "px"
            },0);
        });
    }
    
    main();
});


$(function(){
    //【加盟资讯】Tab鼠标经过效果
    function main(){
        $(".div-zhome-jmzx .div-col-2x5 ul li").hover(function(){
            if ($(this).hasClass("link-notab") === false) {//最后一个是【更多】链接
                var idx = $(this).index();
                $(this).addClass("link-active").siblings().removeClass("link-active");
                $("#div-zhome-rmxm .div-content .content-tab").eq(idx).removeClass("hidden").siblings().addClass("hidden");
            }
        }, function(){
        });
    }
    
    main();
});

//临时新添滚动
//原登录/注册位置（ 滚动文字广告）
function autoScroll(obj, ul_bz){
            $(obj).find(ul_bz).animate({
                marginTop : "-30px"
            },500,function(){
                $(this).css({marginTop : "0px"}).find("li:first").appendTo(this);
            })
        };
$(function(){
        setInterval('autoScroll("#hornDiv", "#hornUl")',3000)
});
//资讯滚动
function zxScroll(obj, ul_bz){
            $(obj).find(ul_bz).animate({
                marginTop : "-20px"
            },3000,function(){
                $(this).css({marginTop : "0px"}).find("a:first").appendTo(this);
            })
        };
$(function(){
        setInterval('zxScroll("#zxScroll", "#zxScrollContent")',1)
});
//banner倒计时
//var t=3;//设定跳转的时间 
//setInterval("refer()",1000); //启动1秒定时 
//function refer(){  
//  if(t==0){ 
//     $(".banner_bottom").slideUp(1500,"swing");
//  } 
//	document.getElementById('timeNumber').innerHTML= t; // 显示倒计时 
//  t--; // 计数器递减 
//}

//add 78列表 榜中榜
$(function(){
	var _contLD = $('.cont_l dd');
	var _contRL = $('.cont_r li');
	for( i = 0 ; i<=_contLD.length ; i++){
		if(i > 5 && i < 9 || i > 14 && i < 18 || i > 23 && i < 27){
			_contLD.eq(i).addClass('red');
		};	
	};
	for( i = 0 ; i<=_contRL.length ; i++){
		if(i > 17 && i < 24 || i > 35 && i < 42 || i > 53 && i < 60 || i > 71 && i < 78 ||  i > 89 && i < 96 || i > 107 && i < 114){
			_contRL.eq(i).addClass('red');
		};	
	};
})

$(function(){
	//搜索框功能
	$("#titleselect .inputbox .selectboxdown1 ul li").on("click",function(){
		$("#titleselect .inputbox .selectdropdown p").text($(this).text())
		$("#titleselect .inputbox").find(".selectboxdown1").slideUp()
	})
	$("#titleselect .inputbox .selectdropdown .icon1").show()
	$("#titleselect .inputbox .selectdropdown").on("click",function(){
		if($(this).next().is(":visible")){
			$(this).next().stop().slideUp()
		}else{
			$(this).next().hide().stop().slideDown()
			$(this).find(".icon2").show()
			$(this).find(".icon1").hide()
		}
		if($(this).next().next().is(":visible")){
			$(this).next().next().slideUp()
		}
	})
	
	$("#titleselect .inputbox input").on("click",function(){
		if($(this).parent().find(".selectboxdown1").is(":visible")){
			$(this).parent().find(".selectboxdown1").stop().slideUp()
		}
		if($(this).parent().find(".selectboxdown2").is(":visible")){
			$(this).parent().find(".selectboxdown2").stop().slideUp()
		}else{
			$(this).parent().find(".selectboxdown2").slideDown()
		}
	})
	$(".inputbox").hover(function(){},function(){
		$(this).find(".icon2").hide()
		$(this).find(".icon1").show()
		$(this).find(".selectboxdown1").slideUp()
		$(this).find(".selectboxdown2").slideUp()
	})
	
	$("#titleselect .inputbox .selectboxdown2 .selectlist").on("click",function(){
//		$(".inputbox input").val($(this).find("p").text())
		$(this).parent().slideUp()
//		$(".inputbox input").focus()
	})
})

