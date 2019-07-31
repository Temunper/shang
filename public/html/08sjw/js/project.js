/*留言大表单*/
function quickmsgsubmit3() {
    //判断是否选中
    /* if($('#msgform #checkbox').prop('checked') === false) {
                 alert("请选择我同意将我的联系方式推荐给商家！");
                 return false;
            } */
    //是否含有中文（也包含日文和韩文）
    var reName = /^[a-zA-Z\u4e00-\u9fa5\uF900-\uFA2D ]{1,20}$/;
    if (reName.test($("#Name").val()) === false) {
        alert("请输入正确的姓名！");
        $("#Name").focus().select();
        return false;
    }
    //支持手机号码，3-4位区号，7-8位直播号码，1－4位分机号
    var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
    if (reTel.test($("#Tel").val()) === false) {
        alert("请输入正确的电话号码！");
        $("#Tel").focus().select();
        return false;
    }

    /*提交大表单*/
    $("#imgBtnUp").val("正在提交...");
    var a = {$project_info.project_id};
    $.ajax({
        type: "post",
        url: "/front/message/record_number?project_id=" + a,
        dataType: "json",
        data: $("#msgform").serialize(),
        success: function (data) {
            if (data.code == 200) {
                alert(data.msg);
                $("#imgBtnUp").val("提交留言");
            }
        },
        error: function () {
            alert('提交失败');
        }
    });

}
/*留言小表单*/
function quickmsgsubmit2() {

    var reName = /^[a-zA-Z\u4e00-\u9fa5\uF900-\uFA2D ]{1,20}$/;
    if (reName.test($("#Name1").val()) === false) {
        alert("请输入正确的姓名！");
        $("#Name1").focus().select();
        return false;
    }
    //支持手机号码，3-4位区号，7-8位直播号码，1－4位分机号
    var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
    if (reTel.test($("#Tel1").val()) === false) {
        alert("请输入正确的电话号码！");
        $("#Tel1").focus().select();
        return false;
    }

    /*提交小表单*/
    $("#imgBtnUp1").val("正在提交...");
    var a = {$project_info['project_id']};
    $.ajax({
        type: "post",
        url: "/front/message/record_number?project_id=" + a,
        dataType: "json",
        data: $("#msgform1").serialize(),
        success: function (data) {
            if (data.code == 200) {
                alert(data.msg);
                $("#imgBtnUp1").val("提交留言");
            } else {
                alert(data.msg);
            }
        }
    });

    //提交电话号码
    /*      $("#Free_phone_btn_1").click(function () {
              var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
              if (reTel.test($("#Free_phone_text_1").val()) === false) {
                  alert("请输入正确的电话号码！");
                  $("#Free_phone_text_1").focus().select();
                  return false;
              }

              /!*  /!*检查验证码*!/!*!/
              if ($('#Free_code__input_1').val() === '') {
                  alert('请输入验证码');
                  $("#small_yzmbox").show();

                  return false;
              }
              /!*       /!*检查验证码*!/!*!/
    /!*          var mobilephone = $("#Free_phone_text_1").val();
              $.ajax({
                  url: prefix + "cms/jm/messageinsert",
                  type: "post",
                  "&MessageSource=XQ005-002"+"&Code=" + $('#Free_phone_text_1').val(),
                  dataType: "json",
                  //数据类型为jsonp
                  data: {
                      ProjectID: ProjectID,
                      URL: window.location.href,
                      URLTitle: document.title,
                      Tel: mobilephone,
                      MessageSource: 'XQ005-002',
                      Code: $('#Free_phone_text_1').val()
                  },
                                           // jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
                  //                          jsonpCallback: "jsonpCallback",
                  success: function (data) {
                      if (data.msg == '验证码不能为空且必须是数字') {
                          $("#small_yzmbox").show();
                      }
                      alert(data.msg);
                  },
                  error: function () {
                      alert('fail3');
                  }
              });*!/
          });*/
    /*  /!* /
      /提交电话号码2 （详情页左立即联系我）*/
    /*  $("#Free_phone_btn1").click(function () {
          var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
          if (reTel.test($("#Free_phone_text1").val()) === false) {
              alert("请输入正确的电话号码！");
              $("#Free_phone_text1").focus().select();
              return false;
          }
          var mobilephone = $("#Free_phone_text1").val();
          $.ajax({
              type: "get",
              async: false,
              url: prefix + "jm/msgsubmit?ProjectID=" + ProjectID + "&URL=" + window.location
                      .href + "&URLTitle=" + document.title + "&Tel=" + mobilephone +
                  "&MessageSource=XQ003-003",
              dataType: "jsonp", //数据类型为jsonp
              jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
              jsonpCallback: "jsonpCallback",
              success: function (data) {
                  alert("呼叫成功,请等候来电");
              },
              error: function () {
                  alert('fail4');
              }
          });

      });*/
}