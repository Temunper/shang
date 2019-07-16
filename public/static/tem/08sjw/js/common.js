//search start
$(function(){
  $(".btn-submit").click(function(){
    var keywordVal = $(".selectinput").val();
      keywordVal = encodeURI(keywordVal);
    var keyword = ( keywordVal == "") ? "" : (keywordVal == "输入你想找的品牌") ? "" : keywordVal;
    params = {
      "pageIndex":"",
      "investMoney":$("#searchInvestMoney").val(),
      "innerCode":$("#searchCatalogInnerCode").val(),
      "query": keyword ,
      };
    AllSearch(params);  
  });
//  var myInput = $('.selectinput').get();
//  if (myInput == document.activeElement) {
//     $(document).keyup(function(event){
//      if(event.keyCode =="13"){
//        $(".btn-submit").trigger("click");
//      }
//    });
//  }; 
  $('.selectinput').bind('keyup', function(event) {
  　　if (event.keyCode == "13") {
  　　　　$('.btn-submit').click();
  　　}
  });

  function AllSearch(params){
    var p = params.pageIndex||'1';
    var i = params.investMoney||'0';
    var c = params.innerCode||'0';
    var q = params.query||'';
    var url = prefix+"project/search/"+p+"_"+i+"_"+c+".shtml";
    if(q!=""){
      url +="?q="+q+"&s=4";
      
    }else{
      url += "?s=4";
    }
  //window.location.href = url;
   window.open(url, '_blank')
  $("#searchCatalogInnerCode").val('');
  }

  $(".selectboxdown1").on("click" ,"li" ,function(){
    $("#searchCatalogInnerCode").val($(this).attr("data-id"));
  });
  

  CodeTimmer('ctrl_code_btn');
  CodeCheck('ctrl_code_input');


});
//search end

/**表单验证码*/
function CodeTimmer(className) {
  var time = 60,
    counting = false,
    timmer = null,
    $list = $('.' + className);
  
  $list.on('click', function(e) {
    var $this = $(e.target);
    var tel = $('#' + $this.data('telinput')).val();
    ajaxVerification(tel);
    handlerClick();
  });

  var handlerClick = function() {
    if (counting)
      return;
    counting = true;
    showAct();
    timmer = setInterval(function() {
      time--;
      if (time > 0) {
        showAct();
      }
      else {
        clear();
      }
    }, 1000);
  }
  var showAct = function(reset) {
    $list.each(function(i, o) {
      if (!reset)
        $(o).text(time + 's').addClass('item-code__btn--disable, ededed');
      else
        $(o).text('点击获取').removeClass('item-code__btn--disable, ededed');
    });
  }
  var clear = function() {
    clearInterval(timmer);
    timmer = null;
    time = 60; 
    counting = false;
    showAct(true);
  }
  var ajaxVerification = function(tel) {
    if (counting) 
      return;
    $.ajax({
      type : "post",  
      url : prefix + 'api/jiameng/submitcode', 
      data:{
        Tel: tel
      },
      dataType : "json",
      success : function(data){  
        alert(data._Message);
      },  
      error:function(data){  
        alert('fail'); 
        console.log(data)
      }  
    }); 
  }
}

function CodeCheck(className) {
  var $list = $('.' + className);
  var reg = /^[0-9]{6}$/;
  $list.each(function(i, o) {
    $(o).data('pass', false);
  });
  $list.on('input propertychange', function(e) {
    var $this = $(e.target);
    var val = $.trim($(e.target).val());
    $this.data('pass', reg.test(val));
  });
}


// var intDiff = 60;//倒计时总秒数量
// var verificationDivTime =  true;
// var verification_submit = $('.verification_submit')
// var verification_bottom_submit = $('.verification_bottom_submit')
   //右上验证码 start
   $('.area-right-tel1').bind('input propertychange',function(){
     var val = $(this).val();
     if(val.length == '11'){
       $('#verification_div').show();
     }else{
       $('#verification_div').hide();
     }  
   })

// $('#verification_div .verification_submit').on('click',function(){
//   if(verificationDivTime == true){
//     var g = {
//       Tel : $('.area-right-tel1').val()
//     };
//     ajaxVerification(g)  
//     verificationDivTime = false;
//     timer(intDiff,verification_submit);
//   }  
// }) 

// //底部验证码 start
	$('#bottomTel').bind('input propertychange',function(){
	var val = $(this).val();
	if(val.length == '11'){
	    $('#verification_bottom').show();
	}else{
	    $('#verification_bottom').hide();
	}
	})
// $('#verification_bottom .verification_bottom_submit').on('click',function(){
//   var g = {
//     Tel : $('#bottomTel').val()
//   };
//   ajaxVerification(g)
//   verificationDivTime = false;
//   timer(intDiff,verification_bottom_submit);
// })
// function ajaxVerification(g){
//   $.ajax({
//       type : "post",  
//        url : prefix + 'api/jiameng/submitcode', 
//        data:g,
//       dataType : "json",
//       success : function(data){  
//          alert("获取成功");
//       },  
//        error:function(data){  
//             alert('fail'); 
//             console.log(data)
//         }  
//   }); 
// }
// /**
//  * @param  {[type]} place   判断是哪一个按钮触发, 取不同的参数
//  */
// function timer(intDiff,dom, type, place){
//     var timers = setInterval(function(){
//       if(intDiff >= 0){
//         if (intDiff <= 9) 
//           intDiff = '0' + intDiff;
//         if (type == '1') {
//           dom.text(intDiff + 's...');
//           dom.addClass('item-code__btn--disable');
//         }
//         else {
//           dom.html('重新获取'+intDiff+'S');
//           dom.addClass('ededed')
//         }
//       }
//       else {
//         if (type == '1') {
//           dom.text('点击获取');
//           dom.removeClass('item-code__btn--disable');
//         }
//         else {
//           dom.removeClass('ededed')
//           dom.html('点击获取');
//         }          
//         verificationDivTime = true;
//         clearInterval(timers)
//         return false
//       }
//       intDiff--;
//     }, 1000);
// }

// $('.item-code__btn, .Free_code__btn').on('click', function() {
//   if(verificationDivTime == true) {
//     var $this = $(this),
//     place = $this.data('type')
//     timer(intDiff, $(this), '1', place);
//     getCode(place);
//     verificationDivTime = false;
//   }    
// });

// function getCode(type) {
//   var tel = '';
//   if (type === 1) 
//     tel = $('#Free_phone_text').val();
//   else if(type === 2) 
//     tel = $('#Tel').val();
//   else if (type === 3)
//     tel = $('#Free_phone_text_1').val();
//   else if (type === 4)
//     tel = $('#Tel1').val();

//   var url = prefix + 'api/jiameng/submitcode';
//   $.ajax({
//     type: "post",
//     async: false,
//     data: {
//       "Tel": tel
//     },
//     url: url,
//     dataType: "JSON", //数据类型为json
//     success: function(data) {
//       alert(data._Message);
//     },
//     error: function() {
//       alert('获取验证码失败!');
//     }
//   });
// }
/**表单验证码*/


// console.log('路有多远，只有心知道,\n最美的旅程，是不断的经历，\n坚持走下去，与梦想者同行,\n请将简历发送至 54360128@qq.com（ 邮件标题：“姓名-应聘XX职位-来自console” ）')
