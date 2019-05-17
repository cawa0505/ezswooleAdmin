@extends('backend.base')

@section('content')
    <div class="layui-card">
        <div class="layui-card-header  layuiadmin-card-header-auto">
            <h2>添加菜单</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">菜单名</label>
                    <div class="layui-input-block">
                        <input type="text" name="menu_name" lay-verify="" autocomplete="off" placeholder="请输入菜单名"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">菜单地址</label>
                    <div class="layui-input-block">
                        <input type="text" name="menu_url" lay-verify="" autocomplete="off" placeholder="请输入菜单地址"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">菜单分组</label>
                    <div class="layui-input-inline">
                        <select name="parent_id[]" lay-filter="parent_id">
                            <option value="0" selected="">一级菜单</option>
                            @foreach($result as $item)
                                <option value="{{$item['menu_id']}}">{{$item['menu_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="layui-input-inline">
                        <select name="parent_id[]" id="townId">
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">标识</label>
                    <div class="layui-input-block">
                        <input type="text" name="menu_identify" lay-verify="" autocomplete="off" placeholder="请输入标识"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">图标</label>
                    <div class="layui-input-block">
                        <input type="text" name="menu_icon" lay-verify="" autocomplete="off" placeholder="请输入图标"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <button class="layui-btn" lay-submit="" lay-filter="admin">立即提交</button>
                            <a class="layui-btn" href="/backend/menu/index">返回</a>
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
                ajaxPost('/backend/menu/store', data.field, '/backend/menu/index');
                return false;
            });

            // 联动事件
            form.on('select(parent_id)', function (data) {
                $.ajax({
                    type: "get",
                    url: "/backend/menu/getSubordinateList",
                    data: {parent_id: data.value},
                    async: false,
                    success: function (result) {
                        let list = result.data;
                        let option = '<option value="" selected>请选择</option>';
                        $.each(list, function (index, value) {
                            option += '<option value="' + +value.menu_id + '">' + value.menu_name + '</option>';
                        });
                        $("#townId").html(option);
                        form.render("select");
                    }
                });
            });

        });
    </script>
@endsection
