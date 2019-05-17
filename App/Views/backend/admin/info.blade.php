@extends('backend.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <h2>用户信息</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form">
                <input type="hidden" name="id" value="{{$result['id']}}">
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" disabled value="{{$result['username'] ??''}}" autocomplete="off" placeholder="请输入用户名" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="password" disabled value="{{$result['password'] ??''}}" autocomplete="off" placeholder="请输入密码" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">手机</label>
                    <div class="layui-input-block">
                        <input type="tel" disabled value="{{$result['mobile'] ??''}}" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                    </div>
                </div>


                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" disabled value="{{$result['email']??''}}" autocomplete="off" placeholder="请输入邮箱" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">角色</label>
                    <div class="layui-input-block">
                        <select name="role" lay-filter="role" disabled>
                            <option value="0" selected="">请选择</option>
                            <option value="1">超级管理员</option>
                            <option value="2">图书管理员</option>
                            <option value="3">仓库管理员</option>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">启用/禁用</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="status" disabled lay-skin="switch"
                               lay-filter="component-form-switchTest" lay-text="ON|OFF">
                        <div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>ON</em><i></i>
                        </div>
                    </div>
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <a class="layui-btn" href="/backend/admin/index">返回</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


