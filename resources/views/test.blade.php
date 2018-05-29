<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ URL::asset('js/md5.js') }}"></script>
    <style>
        form{
            width: 50%;
            margin: 0 auto;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<form class="form" role="form" method="post" action="/">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="form-group">
        <label class="" for="id">学号</label>
        <input type="text" class="form-control" name="id" placeholder="请输入学号">
    </div>
    <div class="form-group">
        <label class="" for="pwd">密码</label>
        <input type="text" class="form-control" name="pwd" placeholder="请输入密码">
    </div>
    <div class="form-group">
        <label class="" for="xdvfb">验证码</label>
        <input type="text" class="form-control" name="xdvfb" placeholder="请输入验证码">
    </div>
    <?php
        $ch = curl_init();
        $url = "http://210.42.121.241/servlet/GenImg";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_REFERER, '');
        curl_setopt($ch, CURLOPT_USERAGENT, 'Baiduspider');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $contents = curl_exec($ch);
        curl_close($ch);
        preg_match('/JSESSIONID=(.*);/', $contents, $jsession);
        preg_match('/sto-id-20480=(.*);/', $contents, $stoid);
        $stoid = substr($stoid[1], 0, 12);
        $jsession = $jsession[1];
        $base = base64_encode(ltrim(explode('Path=/', $contents)[2]));
        echo "<input type='hidden' name='jsession' value='$jsession'>";
        echo "<input type='hidden' name='stoid' value='$stoid'>";
        echo "<img src='data:image/jpg;base64,$base' />";
    ?>
    <button type="submit" class="btn btn-default" id="submit" onclick="beforsubmit()">确定</button>
</form>
<?php
print_r($result);

?>

<script>


    function beforsubmit(){
        if ($("input[name='pwd']").val() !=""){
            $("input[name='pwd']").val(hex_md5($("input[name='pwd']").val()));}
    }
</script>
</body>
</html>