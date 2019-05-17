@extends('backend.base')

@section('content')
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">角色名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="username" placeholder="请输入" autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-useradmin" lay-submit="" lay-filter="LAY-user-front-search">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="layui-card-body">
            <table class="layui-hide" id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="toolbarDemo">
                <div class="layui-btn-container">
                    <button class="layui-btn layui-btn-sm layui-btn-normal" id="create" lay-event="create">添加
                    </button>
                    <button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete" lay-event="delete">批量删除
                    </button>
                </div>
            </script>

            <script type="text/html" id="options">
                <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
            </script>
        </div>
    </div>
@endsection

@section('script')
    <script>
        layui.use(['layer', 'table', 'form', 'jquery'], function () {
            let $ = layui.jquery; //独立版的layer无需执行这一句
            let layer = layui.layer;
            let form = layui.form;
            let table = layui.table;

            table.render({
                elem: '#dataTable'
                , url: '/backend/role/data'
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter', 'print', 'exports']
                , loading: true
                , page: true
                , title: '角色数据表'
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'role_id', title: 'ID', width: 80, fixed: 'left', sort: true}
                    , {field: 'role_name', title: '角色名称', width: 170}
                    , {field: 'role_desc', title: '角色描述', width: 180}
                    , {fixed: 'right', title: '操作', toolbar: '#options', width: 170, align: 'center'}
                ]]
            });
            $("#create").click(function () {
                location.href = '/backend/role/create';
            });
            //按钮批量删除
            $("#listDelete").click(function () {
                let ids = [];
                let hasCheck = table.checkStatus('dataTable');
                let hasCheckData = hasCheck.data;
                if (hasCheckData.length > 0) {
                    $.each(hasCheckData, function (index, element) {
                        ids.push(element.role_id)
                    })
                }
                if (ids.length > 0) {
                    layer.confirm('确认删除吗？', function (index) {
                        ajaxPost('/backend/role/delete', {id: ids}, '/backend/role/index');
                    });
                } else {
                    layer.msg('请选择删除项', {icon: 5})
                }
            });

            //监听行工具事件
            table.on('tool(dataTable)', function (obj) {
                let data = obj.data;
                switch (obj.event) {
                    case 'edit':
                        location.href = '/backend/role/edit?id=' + data.role_id;
                        break;
                    case 'delete':
                        layer.confirm('确认删除吗？', function (index) {
                            ajaxPost('/backend/role/delete', {id: data.role_id}, '/backend/role/index');
                        });
                        break;
                }
            });
            //监听搜索
            form.on('submit(LAY-user-front-search)', function (data) {
                let field = data.field;
                ajaxGet('/backend/role/data', {keywords: field}, '/backend/role/index');
                //执行重载
                table.reload('LAY-user-manage', {
                    where: field
                });
            });
        })
    </script>
@endsection



