<?php
/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 8:48
 */

namespace App\HttpController\Backend;

use App\Service\Backend\AdminService;
use App\Service\Backend\RoleService;

/**
 * ClassDescription
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 2019/4/18 15:52
 */
class Admin extends Base
{
    /**
     * 管理员首页
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 15:52
     */
    public function index()
    {
        $this->render('backend.admin.index');
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
        $response = loader(AdminService::class)->getSearchList($request);
        $this->responseJson($response);
    }

    /**
     * 获取添加管理员页面
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/18 15:52
     */
    public function create()
    {
        $role = loader(RoleService::class)->getAll();
        $this->render('backend.admin.create', ['role' => $role]);
    }

    /**
     * 添加管理员
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 15:04
     */
    public function store()
    {
        $request  = $this->request()->getParsedBody();
        $response = loader(AdminService::class)->create($request);
        $this->responseJson($response);
    }

    /**
     * 编辑管理员
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:17
     */
    public function edit()
    {
        $id       = $this->request()->getQueryParam('id');
        $response = loader(AdminService::class)->getOne($id);
        $role     = loader(RoleService::class)->getAll();
        $this->render('backend.admin.edit', [
            'result' => $response['data'],
            'role'   => $role
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
        $response = loader(AdminService::class)->update($request);
        $this->responseJson($response);
    }

    /**
     * 删除管理员
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 2019/4/19 16:17
     */
    public function delete()
    {
        $id       = $this->request()->getParsedBody('id');
        $response = loader(AdminService::class)->delete($id);
        $this->responseJson($response);
    }

    public function info()
    {
        $this->render('backend.admin.info', ['result' => $this->adminInfo]);
    }
}