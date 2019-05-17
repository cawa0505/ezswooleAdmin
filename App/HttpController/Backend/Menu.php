<?php
/**
 * ClassDescription
 * @category Category
 * @package Package
 * @author qap <qiuapeng921@163.com>
 * @license http://wiki.com/index.php
 * @link http://127.0.0.1:8000/index
 * @Date 19-4-29 上午9:36
 */

namespace App\HttpController\Backend;

use App\Service\Backend\MenuService;

/**
 * Class Menu
 * @package App\HttpController\Backend
 */
class Menu extends Base
{
    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function index()
    {
        $this->render('backend.menu.index');
    }

    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function data()
    {
        $request  = $this->request()->getQueryParams();
        $response = loader(MenuService::class)->getSearchList($request);
        $this->responseJson($response);
    }


    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function create()
    {
        $response = loader(MenuService::class)->getSubordinateList(0);
        $this->render('backend.menu.create', [
            'result' => $response ? $response['data'] : []
        ]);
    }

    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function store()
    {
        $request  = $this->request()->getParsedBody();
        $response = loader(MenuService::class)->create($request);
        $this->responseJson($response);
    }

    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function edit()
    {
        $id       = $this->request()->getQueryParam('id');
        $response = loader(MenuService::class)->getOne($id);
        $menu     = loader(MenuService::class)->getSubordinateList(0);
        $this->render('backend.menu.edit', [
            'result' => $response['data'],
            'menu'   => $menu ? $menu['data'] : []
        ]);

    }

    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function update()
    {
        $request  = $this->request()->getParsedBody();
        $response = loader(MenuService::class)->update($request);
        $this->responseJson($response);

    }

    /**
     *
     * @return void
     *
     * @author qap <qiuapeng921@163.com>
     * @date 19-5-5 上午9:27
     */
    public function delete()
    {
        $id       = $this->request()->getParsedBody('id');
        $response = loader(MenuService::class)->delete($id);
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
        $response = loader(MenuService::class)->getSubordinateList($parentId);
        $this->responseJson($response);
    }
}