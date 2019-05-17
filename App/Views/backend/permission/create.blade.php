@extends('backend.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header  layuiadmin-card-header-auto">
            <h2>添加管理员</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">权限名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="" autocomplete="off" placeholder="请输入权限名称"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">权限分组</label>
                    <div class="layui-input-inline">
                        <select name="parent_id[]" lay-filter="parent_id">
                            <option value="0" selected="">一级权限</option>
                            @foreach($permission as $item)
                                <option value="{{$item['permission_id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="parent_id[]" id="townId">
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">访问地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="url" lay-verify="" autocomplete="off" placeholder="请输入访问地址"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <button class="layui-btn" lay-submit="" lay-filter="admin">立即提交</button>
                            <a class="layui-btn" href="/backend/permission/index">返回</a>
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
                ajaxPost('/backend/permission/store', data.field, '/backend/permission/index');
                return false;
            });

            // 联动事件
            form.on('select(parent_id)', function (data) {
                $.ajax({
                    type: "get",
                    url: "/backend/permission/getSubordinateList",
                    data: {parent_id: data.value},
                    async: false,
                    success: function (result) {
                        let list = result.data;
                        let option = '<option value="" selected>请选择</option>';
                        $.each(list, function (index, value) {
                            console.log(value);
                            option += '<option value="' + value.permission_id + '">' + value.name + '</option>';
                        });
                        $("#townId").html(option);
                        form.render("select");
                    }
                });
            });
        });
    </script>
@endsection
