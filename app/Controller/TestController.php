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

use function Hyperf\Config\config;
use function \Hyperf\Support\env;

#[Controller(prefix: "/test")]
class TestController extends AbstractController
{
    #[Inject]
    public MessageLogic $messageLogic;

    #[RequestMapping(path: "index", methods: "get,post")]
    public function postText()
    {
        $configStr = config('nacos_config');  // JSON 字符串
        $configArr = json_decode($configStr, true);  // 转成数组
        return [
            "config" => $configArr,
            "app_name" => env('APP_NAME'),
        ];
    }
}
