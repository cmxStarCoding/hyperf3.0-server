<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\AfterWorkerStart;
use Hyperf\Guzzle\ClientFactory;
use Psr\Container\ContainerInterface;
use function Hyperf\Support\env;
class GrpcRegisterServiceListener implements ListenerInterface
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function listen(): array
    {
        return [
            AfterWorkerStart::class,
        ];
    }

    public function process(object $event): void
    {
        // 仅主 Worker 注册
        if ($event->workerId !== 0) {
            return;
        }

        $host = "10.200.15.106";
        $port = 9503;
        $serviceName = $grpcConfig['service_name'] ?? 'grpc_service';
        $client = $this->container->get(ClientFactory::class)->create([
            'base_uri' => "http://" . env('NACOS_HOST', '127.0.0.1') . ":" . env('NACOS_PORT', 8848) . "/nacos/v1/ns/",
            'timeout' => 5,
        ]);

        try {
            $response = $client->post('instance', [
                'form_params' => [
                    'serviceName' => $serviceName,
                    'ip' => $host,
                    'port' => $port,
                    'groupName' => env('NACOS_GROUP', 'DEFAULT_GROUP'),
                    'namespaceId' => env('NACOS_NAMESPACE_ID', 'public'),
                    'ephemeral' => false,
                ],
            ]);

            $body = (string) $response->getBody();
            printf("[GrpcRegister] Registered service %s to Nacos: %s\n", $serviceName, $body);
        } catch (\Throwable $e) {
            printf("[GrpcRegister] Failed to register service %s: %s\n", $serviceName, $e->getMessage());
        }
    }
}
