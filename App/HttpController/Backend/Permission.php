<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/23 11:23
 */

namespace App\HttpController\Backend;


use App\Service\Backend\PermissionService;

class Permission extends Base
{
    public function index()
    {
        $this->render('backend.permission.index');
    }

    /**
     * 首页数据
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:17
     */
    public function data()
    {
        $request  = $this->request()->getQueryParams();
        $response = loader(PermissionService::class)->getSearchList($request);
        $this->responseJson($response);
    }

    /**
     * 获取添加页面
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 15:52
     */
    public function create()
    {
        $response = loader(PermissionService::class)->getSubordinateList(0);
        $this->render('backend.permission.create', [
            'permission' => $response['data'] ?? []
        ]);
    }

    /**
     * 添加角色
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 15:04
     */
    public function store()
    {
        $request  = $this->request()->getParsedBody();
        $response = loader(PermissionService::class)->create($request);
        $this->responseJson($response);
    }

    /**
     * 编辑角色
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:17
     */
    public function edit()
    {
        $id         = $this->request()->getQueryParam('id');
        $response   = loader(PermissionService::class)->getOne($id);
        $permission = loader(PermissionService::class)->getSubordinateList(0);
        $this->render('backend.permission.edit', [
            'result'     => $response['data'],
            'permission' => $permission['data'] ?? []
        ]);
    }

    /**
     * 保存修改
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:17
     */
    public function update()
    {
        $request  = $this->request()->getParsedBody();
        $response = loader(PermissionService::class)->update($request);
        $this->responseJson($response);
    }


    /**
     * 删除角色
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:17
     */
    public function delete()
    {
        $id       = $this->request()->getParsedBody('id');
        $response = loader(PermissionService::class)->delete($id);
        $this->responseJson($response);
    }

    /**
     * 获取下级列表
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:29
     */
    public function getSubordinateList()
    {
        $parentId = $this->request()->getQueryParam('parent_id');
        $response = loader(PermissionService::class)->getSubordinateList($parentId);
        $this->responseJson($response);
    }
}