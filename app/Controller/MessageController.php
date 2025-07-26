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

use App\Logic\MessageLogic;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\Di\Annotation\Inject;

#[Controller(prefix: "/wxchat/message")]
class MessageController extends AbstractController
{
    #[Inject]
    public MessageLogic $messageLogic;

    #[RequestMapping(path: "postText", methods: "get,post")]
    public function postText()
    {
        return $this->messageLogic->postText($this->request->all());
    }

    #[RequestMapping(path: "postImage", methods: "get,post")]
    public function postImage(){
        return $this->messageLogic->postImage($this->request->all());
    }

    #[RequestMapping(path: "postFile", methods: "get,post")]
    public function postFile(){
        return $this->messageLogic->postFile($this->request->all());
    }

}
