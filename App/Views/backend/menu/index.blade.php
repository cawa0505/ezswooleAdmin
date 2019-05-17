@extends('backend.base')

@section('content')
    <div class="layui-card">
        <div class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="demoTable">
                <div class="layui-inline">
                    <label class="layui-form-label">菜单名</label>
                    <div class="layui-inline">
                        <input class="layui-input" name="menu_name" id="menu_name" autocomplete="off">
                    </div>
                </div>
                <button class="layui-btn" data-type="reload">搜索</button>
            </div>
        </div>
        <div class="layui-card-body">
            <table class="layui-hide" id="dataTable" lay-filter="dataTable"></table>
            <script type="text/html" id="toolbarDemo">
                <div class="layui-btn-container">
                    <button class="layui-btn layui-btn-sm layui-btn-normal" id="create" lay-event="create">添加
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
            let util = layui.util;

            table.render({
                elem: '#dataTable'
                , url: '/backend/menu/data'
                , toolbar: '#toolbarDemo'
                , defaultToolbar: ['filter', 'print', 'exports']
                , loading: true
                , page: true
                , title: '用户数据表'
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'menu_id', title: 'ID', width: 80, fixed: 'left', sort: true}
                    , {field: 'menu_name', title: '菜单名', width: 170}
                    , {field: 'menu_icon', title: '菜单图标', width: 180}
                    , {field: 'menu_url', title: '菜单地址', width: 180}
                    , {
                        field: 'last_login_time', title: '添加时间', width: 220, templet: function (d) {
                            return util.toDateString(d.create_time * 1000);
                        }
                    }
                    , {
                        field: 'last_login_time', title: '修改时间', width: 220, templet: function (d) {
                            return util.toDateString(d.update_time * 1000);
                        }
                    }
                    , {fixed: 'right', title: '操作', toolbar: '#options', width: 170, align: 'center'}
                ]]
                , id: 'testReload'
            });

            $("#create").click(function () {
                location.href = '/backend/menu/create';
            });

            //监听行工具事件
            table.on('tool(dataTable)', function (obj) {
                let data = obj.data;
                console.log(data);
                switch (obj.event) {
                    case 'edit':
                        location.href = '/backend/menu/edit?id=' + data.menu_id;
                        break;
                    case 'delete':
                        layer.confirm('确认删除吗？', function (index) {
                            ajaxPost('/backend/menu/delete', {id: data.menu_id}, '/backend/menu/index');
                        });
                        break;
                }
            });

            //监听性别操作
            form.on('switch(status)', function (obj) {
                console.log(obj);
            });

            //监听搜索
            let active = {
                reload: function () {
                    //执行重载
                    table.reload('testReload', {
                        page: {
                            curr: 1 //重新从第 1 页开始
                        }
                        , where: {
                            keywords: {
                                menu_name: $('#menu_name').val()
                            }
                        }
                    });
                }
            };

            $('.demoTable .layui-btn').on('click', function () {
                let type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });

        })
    </script>
@endsection



