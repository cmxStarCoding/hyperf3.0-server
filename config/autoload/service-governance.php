<?php
return [
    'consul' => [
        'driver' => Hyperf\ServiceGovernanceConsul\ConsulDriver::class,
        'config' => [
            'host' => env('CONSUL_HOST', '10.200.16.50'),
            'port' => env('CONSUL_PORT', 8500),
            'register' => [
                'host' => env('SERVICE_ADDRESS', '10.200.16.50'), // 这里写宿主机IP，通知Consul你服务对外地址
                'port' => 9504,
                'check' => [
                    'http' => 'http://10.200.16.50:9504/health', // 健康检查地址
                    'interval' => '10s',
                    'timeout' => '1s',
                ],
            ],
        ],
    ],
];
