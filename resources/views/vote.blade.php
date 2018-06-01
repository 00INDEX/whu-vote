@extends('master')

@section('content')

    @foreach($teachers as $teacher)

        @if($teacher->id == 1 || $teacher->id == 12 || $teacher->id == 25 || $teacher->id == 39)
<!--
多个奖项分幅时使用
        <div class="panel-group" id="accordion" style="margin: 0 auto !important;width: 440px !important;">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion"
                           href="{{ "#aaa" . $teacher->id }}">
                            {{ $teacher->id }}
                        </a>
                    </h4>
                </div>
                <div id="{{ "aaa" . $teacher->id }}" class="panel-collapse collapse in">
                -->
        @endif




                    @if(($teacher->id - 1) % 2 == 0)
                        <div class="voteBox" style="border-left-style: solid;border-left-color: green;border-left-width: 10px;">
                            @else
                                <div class="voteBox" style="border-right-style: solid;border-right-color: green;border-right-width: 10px;">
                                    @endif

                                    <div style="width: 400px;height: auto;margin: 0 auto">
                                        <div class="face">
                                            <img src="{{ $teacher->pic }}" width="350px" style="box-shadow: 5px 5px 2px #888888;">
                                        </div>
                                        <p style="font-size: 30px;margin-top: 10px">{{ $teacher->name }}</p>
                                        <div style="width: 130px;height: 2px;background-color: green;margin: 0 auto"></div>
                                        <!--
                                        <p style="font-size: 15px;float: left;margin-left: 100px;position: absolute;margin-top: 95px">{{ $teacher->id }}</p>
                                        <div style="width: 100px;height: 2px;background-color: green;float: left;margin-left: 100px;position: absolute;margin-top: 115px"></div>
                                        -->
                                        <div class="info"><pre>{{ $teacher->award }}</pre></div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                   href="#collapse{{ $teacher->id }}">
                                                    介绍
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse{{ $teacher->id }}" class="panel-collapse collapse in">
                                            <div class="panel-body">{{ $teacher->introduction }}</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-lg vote" name="vote" vote="{{ $teacher->id }}" id="{{ $teacher->id }}">投票</button>
                                </div>

            @if($teacher->id == 11 || $teacher->id == 24 || $teacher->id == 38 || $teacher->id == 47)
                                <!--
            多个奖项分幅时使用
                        </div>
                </div>
        </div>
        -->
            @endif
    @endforeach
            <button type="button" class="btn btn-primary btn-lg btn-block" id="votesubmit">提交</button>
                <script>
                    $(document).ready(function () {
                        $("div.panel-collapse").collapse('hide');
                    })
                    $(document).ready(function () {
                        var ua = navigator.userAgent.toLowerCase();
                        var isWeixin = ua.indexOf('micromessenger') != -1;
                        if (isWeixin) {
                            swal("请用浏览器打开！","由于兼容性问题，请点击右上角，使用浏览器打开","info");
                        }else{
                            return false;
                        }
                    })

                </script>



    @if(!session('code'))
        <script>
            $(document).ready(function () {
                $("button[name='vote']").click(function () {
                    swal({
                        title: "请先登陆，微信用户请用浏览器打开否则会跳出",
                        showConfirmButton: false,
                        text: "<form class=\"form-horizontal\" id=\"auth\" role=\"form\" method=\"post\" action=\"/\">\n" +
                        "<input type=\"hidden\" name=\"_token\" value=\"{{csrf_token()}}\">\n" +
                        "<input type=\"hidden\" name=\"action\" value=\"1\">\n" +
                        "<div class=\"form-group\">\n" +
                        "<label class=\"\" for=\"id\">学号</label>\n" +
                        "<input type=\"text\" name=\"id\" style=\"display: inline-block !important;width: 70%\" placeholder=\"请输入学号\">\n" +
                        "</div>\n" +
                        "<div class=\"form-group\">\n" +
                        "<label class=\"\" for=\"pwd\">密码</label>\n" +
                        "<input type=\"password\" name=\"pwd\" style=\"display: inline-block !important;width: 70%\" placeholder=\"教务系统密码\">\n" +
                        "</div>\n" +
                        "<div class=\"form-group\">\n" +
                        "<label class=\"\" for=\"xdvfb\">验证码</label>\n" +
                        "<input type=\"text\" name=\"xdvfb\" style=\"display: inline-block !important;width: 70%\" placeholder=\"区分大小写\">\n" +
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
                            "<button type=\"submit\" class=\"btn btn-default\" id=\"submit2\">确定</button>\n"+ "</form>",
                        html: true
                    });
                });
            });
        </script>
    @endif

    @if(session('error') == "1")
    <script>
        $(document).ready(function () {
           swal("登陆失败", "您填写的信息有误，请检查一下密码是否为教务系统密码（初始密码为8位生日），验证码要区分大小写，大写的I和小写的l很难分清，系统由于服务器性能原因速度很慢，如有卡顿请耐心等待，我也感觉很绝望了", "error");
        });
    </script>
    @endif

    @if(session('error') == "2")
        <script>
            $(document).ready(function () {
                swal("登陆成功", "请开始投票", "success");
            });
        </script>
    @endif

                                @if(session('code'))

        <script>
            var choice = new Array();
            $(document).ready(function () {
                $("button[name='vote']").click(function () {
                    var vote = $(this).attr('id');
                    if (choice.indexOf("" + vote) != -1){
                        choice.splice(choice.indexOf("" + vote), 1);
                        $("button#" + vote).removeClass("active");
                        $("button#" + vote).text("投票");
                        return true;
                    }
                    if (choice.length == 10){
                        var pop = choice.pop();
                        $("button#" + pop).removeClass("active");
                        $("button#" + pop).text("投票");
                    }
                    choice.unshift(vote);
                    $(this).addClass("active");
                    $(this).text("已投票！");
                });
                $("#votesubmit").click(function () {
                    if (choice.length == 0) return false;
                    if (choice.length < 10 ){
                        for (var i = 0;i < 10 - choice.length; i++) choice.unshift(0);
                    };
                    var data = {
                        "_token": "{{csrf_token()}}",
                        "choice": choice,
                        "action": 2
                    };
                    swal({
                            title: "完成投票",
                            text: "确定要提交吗？",
                            type: "info",
                            showCancelButton: true,
                            closeOnConfirm: false,
                            showLoaderOnConfirm: true,
                        },
                        function(){
                            $.ajax({
                                type: "POST",
                                url: "/",
                                data: data,
                                success: function (data) {
                                    swal("提交成功！","谢谢您的参与", "success")
                                }
                            })
                        });
                })
            });
        </script>
                    @endif

@endsection

