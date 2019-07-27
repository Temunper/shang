$(function() {
	
//首页中间轮播鼠标移入后的手风琴效果
$('#flex_box > ul > li').mouseover(function(){
	if(!$(this).hasClass('curr')){
		$('#flex_box > ul > li').removeClass('curr');
		$(this).addClass('curr');
		
		$('.curr').stop(true,true).animate({
			width:692
		},500,'linear');
		$('#flex_box > ul > li').not('.curr').stop().animate({
			width:40
		},500,'linear');
	}
})
$(".show_box").hover(function(){
	$("#flex_box").fadeIn();
	$(this).hide();
});
 
$('.flex_box').hover(function(){
	$(this).show();
},function(){
	$(this).fadeOut();
	$('.show_box').show();
})

//首页banner轮播
$('#dots span').mouseover(function(){
	var _this = $(this);
	var index = $(this).index();
	tio = setTimeout(function(){
		_this.css('background','#12a09b');
		_this.siblings().css('background','#ECECEC');
		if($('#mid_lbt .mid_img').eq(index).is(':hidden')){
			$('#mid_lbt .mid_img').fadeOut();
			$('#mid_lbt .mid_img').eq(index).fadeIn();
			$('#color_box li').fadeOut();
			$('#color_box li').eq(index).fadeIn();
		}
	},300);
})
$('#dots span').mouseout(function(){
	clearTimeout(tio);
})

//首页banner轮播左右箭头轮播图
$('.mid').hover(function(){
	$('.arrow').fadeIn();
},function(){
	$('.arrow').fadeOut();
})

function mid_lbt(){
	var i = 0;
	var dots = $('#dots span');
	var mid_img = $('#mid_lbt .mid_img');
	var color_box = $('#color_box li');
	$('#dots span').mouseover(function(){
		i = $(this).index();
	})
	
	$('.l_arrow').click(function(){
		if(i>=1){
			i--;
			
		}else{
			i=mid_img.length-1;
		}
		ani();
	})
	
	$('.r_arrow').click(function(){
		if(i>=mid_img.length-1){
			i=0;
		}else{
			i++;
		}
		ani();
	})
	
	function ani(){
		dots.siblings().css('background','#ECECEC');
		dots.eq(i).css('background','#12a09b');
		mid_img.fadeOut();
		mid_img.eq(i).fadeIn();
		color_box.fadeOut();
		color_box.eq(i).fadeIn();
	}
	
}

mid_lbt();


//热门排行榜
$('.min_type li').mouseover(function(){
	$(this).css({'background':'#02b6f1'});
	$(this).find('a').css('color','#ffffff');
	$(this).siblings().css({'background':'#ffffff'});
	$(this).siblings().find('a').css('color','#6f6f6f');
	var index = $(this).index();
	$('.type_box ul').each(function(){
		$(this).hide();
		if($(this).index() == index){
			$(this).show();
		}
	})

})


//首页项目分类二级导航显示
$('#item_nav dl').mouseover(function(){
	$(this).addClass('item_nav_active');
	var index = $(this).index();
	$('.nav_box .open_box').each(function(){
		$(this).hide();
		if($(this).index() == index){
			$(this).show();
		}
	})
})
$('#item_nav dl').mouseout(function(){
	$('.nav_box .open_box').hide();
	$(this).removeClass('item_nav_active');
})
$('.nav_box .open_box').each(function(){
	$(this).hover(function(){
		$(this).show();
	},function(){
		$(this).hide();
	})
})


//网站导航

	$("#menu").hover(function() {
		$(".top_nav_list").slideDown();
	},function(){
		$('.top_nav_list').hide();
	});
	$(".top_nav_list").hover(function() {
		$(this).show();
	}, function() {
		$(this).slideUp();
	})
	
	$('#ewm').click(function(){
		if($('.ewm').is(':hidden')){
			$('.ewm').show();
			$(this).addClass('ewm_right');
			$(this).find('span img').attr('src','images/up.png');
		}else{
			$('.ewm').hide();
			$(this).removeClass('ewm_right');
			$(this).find('span img').attr('src','images/down.png');
		}
		
	})

//首页热门招商项目选项卡
$('.hi_nav li').mouseover(function(){
	var index = $(this).index();
	$(this).addClass('active').siblings().removeClass('active');
	$('.hi_tab_box').css('display','none');
	$('.hi_tab_box:eq('+ index +')').css('display','block');
})

});


//列表页的分页效果
$('.page a').click(function(){
	$(this).siblings().css({'background':'#fff','color':'#000'});
	$(this).css({'background':'#01B6B1','color':'#fff'})
})

//详情页的快捷留言效果
$('.quick_m a').click(function(){
	var v = $('.textarea').val();
	$('.textarea').val(v + $(this).text()+"\n");
})



$(".sort ul").each(function(){
	// $(this).children('li').eq(0).addClass('on');
	if( $(this).children('li').length < 11 ){
		$(this).siblings(".sort_more").hide();
	}
});

$(".sort ul li").click(function(){
	$(this).addClass('on').siblings().removeClass('on');
})
$(".sort_more").click(function(){
	$(this).siblings("ul").toggleClass('on');
})

// 详细页获取图片盒子的宽度
var mainImgWidth = $(".div_all").width();
$(".option").width(mainImgWidth);
$(".hot_search").width(mainImgWidth);
$(".header .top").width(mainImgWidth);
$(".message").width(mainImgWidth);
$(".logo_list").width(mainImgWidth);







$(function (){

	var aLi = $('.rightBoxTop .zsbBox ul li');
	var add = $('.rightBoxTop .zsbBox dl dd');

	aLi.hover(function (){
		var i = $(this).index();
		$(this).addClass('active').siblings().removeClass('active');
		add.eq(i).show().siblings().hide();
	});



});









