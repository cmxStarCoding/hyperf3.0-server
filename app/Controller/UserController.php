<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;
use App\Logic\UserLogic;

#[Controller(prefix: "/wxchat/user")]
class UserController extends AbstractController
{
    #[Inject]
    public UserLogic $userLogic;

    #[RequestMapping(path: "list", methods: "get,post")]
    public function list()
    {
        return $this->userLogic->userList($this->request->all());
    }

    #[RequestMapping(path: "getQr", methods: "get,post")]
    public function getIpadQrcode(){
        return $this->userLogic->getIpadQrcode($this->request->all());
    }

    #[RequestMapping(path: "checkQr", methods: "get,post")]
    public function checkQr(){
        return $this->userLogic->checkQr($this->request->all());
    }
}
