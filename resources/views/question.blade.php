@extends('master')

@section('content')
    <form class="form" role="form" style="margin-top: 10px !important;width: 80% !important;">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group">
            <label class="" for="name">姓名</label>
            <input type="text" class="form-control" name="name" placeholder="请输入姓名" msg="姓名" num="1">
        </div>
        <div class="form-group">
            <label class="" for="phone">联系方式</label>
            <input type="text" class="form-control" name="phone" placeholder="请输入联系方式" id="phone" msg="联系方式" num="1">
        </div>
        <div class="form-group">
            <label class="" for="interNmae">推荐老师姓名</label>
            <input type="text" class="form-control" name="interName" placeholder="请输入推荐老师姓名" msg="推荐老师姓名" num="1">
        </div>
        <div class="form-group">
            <label class="" for="interClass">推荐老师所授课程</label>
            <input type="text" class="form-control" name="interClass" placeholder="请输入推荐老师所授课程" msg="推荐老师所授课程" num="1">
        </div>
        <div class="form-group">
            <label class="" for="interCollege">推荐老师所属学院</label>
            <input type="text" class="form-control" name="interCollege" placeholder="请输入推荐老师所属学院" msg="推荐老师所属学院" num="1">
        </div>
        <div class="form-group">
            <label class="" for="reason">推荐原因</label>
            <input type="text" class="form-control" name="reason" placeholder="请输入推荐原因" msg="推荐原因" num="1">
        </div>
        <button class="btn btn-default" id="submit1" name="sysTwo" type="button">确定</button>
    </form>

    <script>
        $(document).ready(function () {

            $("#submit1").click(function () {

                $("input[num$='1']").each(function(n){
                    if($(this).val()=="")
                    {
                        swal("请确认信息已填完整！", $(this).attr('msg') + "不能为空", "error");
                        return false;
                    }
                    else sumit();
                });
            });


        });

        function sumit() {
            swal({
                title: "请先登陆",
                showConfirmButton: false,
                text: "<form class=\"form-horizontal\" id=\"auth\" role=\"form\" method=\"post\" action=\"/questionnaire\">\n" +
                "<input type=\"hidden\" name=\"_token\" value=\"{{csrf_token()}}\">\n" +
                "<div class=\"form-group\">\n" +
                "<label class=\"\" for=\"id\">学号</label>\n" +
                "<input type=\"text\" name=\"id\" style=\"display: inline-block !important;width: 70%\" placeholder=\"请输入学号\">\n" +
                "</div>\n" +
                "<div class=\"form-group\">\n" +
                "<label class=\"\" for=\"pwd\">密码</label>\n" +
                "<input type=\"password\" name=\"pwd\" style=\"display: inline-block !important;width: 70%\" placeholder=\"请输入密码\">\n" +
                "</div>\n" +
                "<div class=\"form-group\">\n" +
                "<label class=\"\" for=\"xdvfb\">验证码</label>\n" +
                "<input type=\"text\" name=\"xdvfb\" style=\"display: inline-block !important;width: 70%\" placeholder=\"请输入验证码\">\n" +
                "</div>\n" +
                <?php

                    $ch = curl_init();
                    $url = "http://210.42.121.241/servlet/GenImg";
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER,1);
                    curl_setopt($ch, CURLOPT_REFERER, '');
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Baiduspider');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 0);
                    curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
                    curl_setopt($ch, CURLOPT_PROXY, $proxy->host . ":" . $proxy->port);
                    $contents = curl_exec($ch);
                    curl_close($ch);
                    preg_match('/JSESSIONID=(.*);/', $contents, $jsession);
                    preg_match('/sto-id-20480=(.*);/', $contents, $stoid);
                    $stoid = substr($stoid[1], 0, 12);
                    $jsession = $jsession[1];
                    $base = base64_encode(ltrim(explode('Path=/', $contents)[2]));
                    echo "\"<input type='hidden' name='jsession' value='$jsession'>\"+";
                    echo "\"<input type='hidden' name='stoid' value='$stoid'>\"+";
                    echo "\"<img src='data:image/jpg;base64,$base' />\"+";
                    ?>
                    "<button type=\"button\" class=\"btn btn-default\" id=\"submit2\">确定</button>\n"+ "</form>",
                html: true
            });


            $("#submit2").on('click', function () {
                $.ajax({
                    type: "POST",
                    url: '/questionnaire',
                    data: $('#auth').serialize(),
                    success: function (data) {
                        if (data['isAuth']){
                            var info = {
                                "_token": "{{csrf_token()}}",
                                "code": data['code'],
                                "name": data['name'],
                                "sex": data['sex'],
                                "idCard": data['idCard'],
                                "birth": data['birth'],
                                "home": data['home'],
                                "college": data['college'],
                                "major": data['major'],
                                "phone": $("input[name='phone']").val(),
                                "interName": $("input[name='interName']").val(),
                                "interClass": $("input[name='interClass']").val(),
                                "interCollege": $("input[name='interCollege']").val(),
                                "reason": $("input[name='reason']").val()
                            };
                            $.ajax({
                                type: "POST",
                                url: '/db',
                                data: info,
                                success: function (data) {
                                    if (data == "1"){
                                        swal("推荐成功！", "感谢您的参与！","success");
                                    }else {
                                        swal("重新推荐成功！", "由于您之前已经填写过此表，推荐信息已经更新！","success")
                                    }
                                }
                            });
                        }
                        else {
                            swal("登陆失败", "您的填写有误，请刷新后重试","error");
                            setTimeout(function(){location.reload();}, 1000);

                        }
                    }
                });
            });
        }


    </script>

    @endsection


