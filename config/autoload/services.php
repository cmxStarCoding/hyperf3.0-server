<?php
return [
    'enable' => [
        // 开启服务发现
        'discovery' => true,
        // 开启服务注册
        'register' => true,
    ],
    // 服务提供者相关配置
    'providers' => [],
    // 服务驱动相关配置
    'drivers' => [
//        'consul' => [
//            'uri' => env("CONSUL_HOST").':8500',//consul服务的位置
//            'token' => '',
//            'check' => [
//                'deregister_critical_service_after' => '90m',
//                'interval' => '1s',
//            ],
//        ],
        'nacos' => [
            // nacos server url like https://nacos.hyperf.io, Priority is higher than host:port
            // 'url' => '',
            // The nacos host info
            'host' => '10.200.15.106',
            'port' => 8848,
            // The nacos account info
            'username' => null,
            'password' => null,
            'guzzle' => [
                'config' => null,
            ],
            'group_name' => 'DEFAULT_GROUP',
//            'namespace_id' => 'namespace_id',
            'namespace_id' => 'b1738c1a-0194-49e3-b118-6716a6de622b',
            'heartbeat' => 5,
            'ephemeral' => false, // 是否注册临时实例
        ],
    ],
];