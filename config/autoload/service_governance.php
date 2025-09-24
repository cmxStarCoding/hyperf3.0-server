<?php

//针对的是RpcServer 或 JsonRpcServer）
use function Hyperf\Support\env;

return [
    'enable' => [
        'register' => true,
        'discovery' => true,
    ],
    'drivers' => [
//        'consul' => [
//            'driver' => Hyperf\ServiceGovernanceConsul\ConsulDriver::class,
//            'config' => [
//                'host' => env('CONSUL_HOST', '127.0.0.1'),
//                'port' => env('CONSUL_PORT', 8500),
//                'register' => [
//                    'host' => env('SERVICE_ADDRESS', '10.200.16.50'), // 通知Consul服务暴露的IP地址（宿主机IP）
//                    'port' => 9504,
//                    'check' => [
//                        'http' => 'http://10.200.16.50:9504/health',
//                        'interval' => '10s',
//                        'timeout' => '1s',
//                    ],
//                ],
//            ],
//        ],
        'nacos' => [
            'host' => env('NACOS_HOST', '127.0.0.1'),   // 容器名或宿主机 IP
            'port' => env('NACOS_PORT', 8848),
            'namespace_id' => env('NACOS_NAMESPACE_ID', 'public'),
            'group_name' => env('NACOS_GROUP', 'DEFAULT_GROUP'),
            'ephemeral' => false,
            'heartbeat' => 5,
            'guzzle' => [
                'config' => null,
            ],
        ],
    ],
];
