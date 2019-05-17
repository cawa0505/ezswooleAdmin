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
use App\Service\Backend\RoleService;

class Role extends Base
{
    public function index()
    {
        $this->render('backend.role.index');
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
        $response = loader(RoleService::class)->getSearchList($request);
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
        $permission = loader(PermissionService::class)->getAll();
        $this->render('backend.role.create', [
            'permission' => $permission ? arrayToTree($permission) : [],
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
        $response = loader(RoleService::class)->create($request);
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
        $response   = loader(RoleService::class)->getOne($id);
        $permission = loader(PermissionService::class)->getAll();
        $this->render('backend.role.edit', [
            'result'     => $response['data'],
            'permission' => $permission ? arrayToTree($permission) : [],
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
        $response = loader(RoleService::class)->update($request);
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
        $response = loader(RoleService::class)->delete($id);
        $this->responseJson($response);
    }
}