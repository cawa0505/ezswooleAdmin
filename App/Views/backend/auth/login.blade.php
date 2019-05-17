@extends('backend.auth.base')

@section('title'){{$title}}@endsection

@section('content')
    <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
        <form action="" method="post">
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                       for="LAY-user-login-username"></label>
                <input type="text" name="username" value="" id="username" lay-verify="required" placeholder="用户名"
                       class="layui-input">
            </div>
            <div class="layui-form-item">
                <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                       for="LAY-user-login-password"></label>
                <input type="password" name="password" id="password" lay-verify="required" placeholder="密码"
                       class="layui-input">
            </div>
            <div class="layui-form-item">
                <div class="layui-row">
                    <div class="layui-col-xs7">
                        <label class="layadmin-user-login-icon layui-icon layui-icon-vercode"
                               for="LAY-user-login-vercode"></label>
                        <input type="text" name="code" id="code" lay-verify="required"
                               placeholder="图形验证码" max="4" class="layui-input">
                    </div>
                    <div class="layui-col-xs5">
                        <div style="margin-left: 10px;">
                            <img src="/auth/code" onclick="this.src='/auth/code?id='+Math.random()"
                                 class="layadmin-user-login-codeimg" id="LAY-user-get-vercode">
                        </div>
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button class="layui-btn layui-btn-fluid" type="button" onclick="login()">登 入</button>
            </div>
            <div class="layui-trans layui-form-item layadmin-user-login-other">
                <label style="color: #00F7DE">社交账号登入</label>
                <a href="javascript:;"><i class="layui-icon layui-icon-login-qq"></i></a>
                <a href="javascript:;"><i class="layui-icon layui-icon-login-wechat"></i></a>
            </div>
        </form>
    </div>
@endsection

@section('script')

    <script type="application/javascript">
        $(document).keyup(function (event) {
            if (event.keyCode === 13) {
                login();
            }
        });

        function login() {
            let username = $("#username").val();
            if (!username) {
                layer.msg("用户名不能为空", {icon: 5, time: 2000});
                return false;
            }
            let password = $("#password").val();
            if (!username) {
                layer.msg("密码不能为空", {icon: 5, time: 2000});
                return false;
            }
            let code = $("#code").val();
            if (!username) {
                layer.msg("验证码不能为空", {icon: 5, time: 2000});
                return false;
            }
            let data = {'username': username, 'password': password, 'code': code};
            ajaxLogin(data);
        }
    </script>
@endsection