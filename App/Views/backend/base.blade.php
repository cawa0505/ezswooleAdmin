<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EsAdmin</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="{{asset('favicon.ico')}}" type="image/x-icon">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="{{asset('static/backend/layuiadmin/layui/css/layui.css')}}" media="all">
    <link rel="stylesheet" href="{{asset('static/backend/layuiadmin/style/admin.css')}}" media="all">
</head>
<body>

<div class="layui-fluid">
    @yield('content')
</div>

<script src="{{asset('static/backend/js/jquery-3.4.0.js')}}"></script>
<script src="{{asset('static/backend/js/jquery.cookie.js')}}"></script>
<script src="{{asset('static/backend/js/common.js')}}"></script>
<script src="{{asset('static/backend/layuiadmin/layui/layui.js')}}"></script>
<script>
    layui.config({
        base: "{{asset('static/backend/layuiadmin/')}}" //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['element', 'form', 'layer', 'table', 'upload', 'laydate'], function () {
    });
</script>
@yield('script')
</body>
</html>



