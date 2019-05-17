@extends('backend.base')

@section('content')
    <style>
        .cate-box {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f0
        }

        .cate-box dt {
            margin-bottom: 10px;
        }

        .cate-box dt .cate-first {
            padding: 10px 20px
        }

        .cate-box dd {
            padding: 0 50px
        }

        .cate-box dd .cate-second {
            margin-bottom: 10px
        }

        .cate-box dd .cate-third {
            padding: 0 40px;
            margin-bottom: 10px
        }
    </style>
    <div class="layui-card">
        <div class="layui-card-header  layuiadmin-card-header-auto">
            <h2>添加角色</h2>
        </div>
        <div class="layui-card-body">
            <form class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称</label>
                    <div class="layui-input-block">
                        <label>
                            <input type="text" name="role_name" lay-verify="" autocomplete="off" placeholder="请输入角色名称"
                                   class="layui-input">
                        </label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">角色描述</label>
                    <div class="layui-input-block">
                        <input type="text" name="role_desc" lay-verify="" autocomplete="off" placeholder="请输入角色描述"
                               class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label style="margin-left: 40px;">权限分配</label>
                    @if($permission)
                        @foreach($permission as $first)
                            <dl class="cate-box" style="margin-left: 110px;">
                                <dt>
                                    <div class="cate-first">
                                        <label for="menu{{$first['permission_id']}}"></label>
                                        <input type="checkbox" name="permission_id[]"
                                               id="menu{{$first['permission_id']}}"
                                               value="{{$first['permission_id']}}"
                                               title="{{$first['name']}}"
                                               lay-skin="primary">
                                    </div>
                                </dt>
                                @if(isset($first['son']))
                                    @foreach($first['son'] as $second)
                                        <dd>
                                            <div class="cate-second">
                                                <label for="menu{{$first['permission_id']}}-{{$second['permission_id']}}"></label>
                                                <input type="checkbox" name="permission_id[]"
                                                       id="menu{{$first['permission_id']}}-{{$second['permission_id']}}"
                                                       value="{{$second['permission_id']}}"
                                                       title="{{$second['name']}}" lay-skin="primary">
                                            </div>
                                            @if(isset($second['son']))
                                                <div class="cate-third">
                                                    @foreach($second['son'] as $three)
                                                        <label for="menu{{$first['permission_id']}}-{{$second['permission_id']}}-{{$three['permission_id']}}"></label>
                                                        <input type="checkbox" name="permission_id[]"
                                                               id="menu{{$first['permission_id']}}-{{$second['permission_id']}}-{{$three['permission_id']}}"
                                                               class="per_{{$second['permission_id']}}"
                                                               value="{{$three['permission_id']}}"
                                                               title="{{$three['name']}}" lay-skin="primary">
                                                    @endforeach
                                                </div>
                                            @endif
                                        </dd>
                                    @endforeach
                                @endif
                            </dl>
                        @endforeach
                    @endif
                </div>

                <div class="layui-form-item layui-layout-admin">
                    <div class="layui-input-block">
                        <div class="layui-footer" style="left: 0;">
                            <button class="layui-btn" lay-submit="" lay-filter="admin">立即提交</button>
                            <a class="layui-btn" href="/backend/role/index">返回</a>
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
            //有一个未选中全选取消选中
            form.on('checkbox', function (data) {
                let check = data.elem.checked;//是否选中
                let checkId = data.elem.id;//当前操作的选项框
                if (check) {
                    //选中
                    let ids = checkId.split("-");
                    if (ids.length === 3) {
                        //第三极菜单
                        //第三极菜单选中,则他的上级选中
                        $("#" + (ids[0] + '-' + ids[1])).prop("checked", true);
                        $("#" + (ids[0])).prop("checked", true);
                    } else if (ids.length === 2) {
                        //第二季菜单
                        $("#" + (ids[0])).prop("checked", true);
                        $(".per_" + ids[1]).each(function (i, ele) {
                            $(ele).prop("checked", true);
                        });
                    } else {
                        //第一季菜单不需要做处理
                        $("input[id*=" + ids[0] + "-]").each(function (i, ele) {
                            $(ele).prop("checked", true);
                        });
                    }
                } else {
                    //取消选中
                    let ids = checkId.split("-");
                    if (ids.length === 2) {
                        //第二极菜单
                        $("input[id*=" + ids[0] + '-' + ids[1] + "]").each(function (i, ele) {
                            $(ele).prop("checked", false);
                        });
                    } else if (ids.length === 1) {
                        $("input[id*=" + ids[0] + "-]").each(function (i, ele) {
                            $(ele).prop("checked", false);
                        });
                    }
                }
                form.render();
            });

            //监听提交
            form.on('submit(admin)', function (data) {
                ajaxPost('/backend/role/store', data.field, '/backend/role/index');
                return false;
            });
        });
    </script>
@endsection
