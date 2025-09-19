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

#[Controller(prefix: "/test")]
class TestController extends AbstractController
{
    #[Inject]
    public MessageLogic $messageLogic;

    #[RequestMapping(path: "index", methods: "get,post")]
    public function postText()
    {
        return [
            "config" => config("im-api"),
        ];
    }
}
