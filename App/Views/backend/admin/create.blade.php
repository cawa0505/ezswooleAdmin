@extends('backend.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header  layuiadmin-card-header-auto">
            <h2>添加管理员</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" lay-verify="" autocomplete="off" placeholder="请输入用户名"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" name="password" lay-verify="" autocomplete="off" placeholder="请输入密码"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">手机</label>
                    <div class="layui-input-block">
                        <input type="tel" name="mobile" lay-verify="" autocomplete="off" placeholder="请输入手机号"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item" pane="">
                    <label class="layui-form-label">所属角色</label>
                    <div class="layui-input-block">
                        @foreach($role as $item)
                        <input type="checkbox" name="role_id[]" value="{{$item['role_id']}}" lay-skin="primary" title="{{$item['role_name']}}">
                        @endforeach
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">启用/禁用</label>
                    <div class="layui-input-block">
                        <input type="radio" name="status" value="0" title="启用" checked="">
                        <input type="radio" name="status" value="1" title="禁用">
                    </div>
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <button class="layui-btn" lay-submit="" lay-filter="admin">立即提交</button>
                            <a class="layui-btn" href="/backend/admin/index">返回</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['jquery', 'form'], function () {
            let form = layui.form;
            //监听提交
            form.on('submit(admin)', function (data) {
                ajaxPost('/backend/admin/store', data.field, '/backend/admin/index');
                return false;
            });
        });
    </script>
@endsection
