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
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\ServiceGovernanceNacos\NacosDriver;
use Psr\Container\ContainerInterface;

#[Controller]
class IndexController extends AbstractController
{
    public function index()
    {

        /** @var NacosDriver $nacos */
        $nacos = $this->container->get(NacosDriver::class);

        // 从 nacos 获取可用服务列表
        $nodes = $nacos->getNodes("", "grpc_service",[]);
        if (empty($nodes)) {
            throw new \RuntimeException("没有找到可用的 gRPC 服务实例");
        }

        $node = $nodes[array_rand($nodes)];
        $target = $node['host'] . ':' . $node['port'];

//        return ["target" => $target];

        //这里的ip可改写为从nacos获取到的服务地址和ip
        $client = new \App\Grpc\HiClient($target, [
            'credentials' => null,
        ]);

        $request = new \Grpc\HiUser();
        $request->setName('hyperf');
        $request->setSex(1);

        /**
         * @var \Grpc\HiReply $reply
         */
        list($reply, $status) = $client->sayHello($request);

        $message = $reply->getMessage();
        $user = $reply->getUser();

        var_dump(memory_get_usage(true));
        return $message;


    }
}
