@extends('backend.base')

@section('content')
    <div class="layui-fluid">
        <div class="layadmin-tips">
            <i class="layui-icon" face>&#xe664;</i>

            <div class="layui-text" style="font-size: 20px;">
                {{$result ?? '系统错误'}}
            </div>
        </div>
    </div>
@endsection