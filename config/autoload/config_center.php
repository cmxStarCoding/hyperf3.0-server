<?php

declare(strict_types=1);

use Hyperf\ConfigApollo\PullMode;
use Hyperf\ConfigCenter\Mode;

return [
    'enable' => (bool) env('CONFIG_CENTER_ENABLE', true),
    'driver' => env('CONFIG_CENTER_DRIVER', 'nacos'),
    'mode' => env('CONFIG_CENTER_MODE', Mode::PROCESS),
    'drivers' => [
        'etcd' => [
            'driver' => Hyperf\ConfigEtcd\EtcdDriver::class,
            'packer' => Hyperf\Utils\Packer\JsonPacker::class,
            // 需要同步的数据前缀
            'namespaces' => [
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/',
            ],
            // `Etcd` 与 `Config` 的映射关系。映射中不存在的 `key`，则不会被同步到 `Config` 中
            'mapping' => [
                // etcd key => config key
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/im-api/im-api.yaml' => 'im-api',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/im-rpc/im-rpc.yaml' => 'im-rpc',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/im-ws/im-ws.yaml' => 'im-ws',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/social-api/social-api.yaml' => 'social-api',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/social-rpc/social-rpc.yaml' => 'social-rpc',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/user-api/user-api.yaml' => 'user-api',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/user-rpc/user-rpc.yaml' => 'user-rpc',
                '/conf/98c6f2c2287f4c73cea3d40ae7ec3ff2/task-mq/task-mq.yaml' => 'task-mq',
            ],
            // 配置更新间隔（秒）
            'interval' => 5,
            'client' => [
                # Etcd Client
                'uri' => 'http://172.22.243.89:3379',
                'version' => 'v3beta',
                'options' => [
                    'timeout' => 10,
                ],
            ],
        ],
        'nacos' => [
            'driver' => Hyperf\ConfigNacos\NacosDriver::class,
            'merge_mode' => Hyperf\ConfigNacos\Constants::CONFIG_MERGE_OVERWRITE,
            'interval' => 3,
            'default_key' => 'nacos_config',
            'listener_config' => [
                'nacos_config' => [
                    'tenant' => '31a036a4-8629-46d4-ad34-fd0b8b40138c', // corresponding with service.namespaceId
                    'data_id' => '456',
                    'group' => 'DEFAULT_GROUP',
                ],
            ],
            'client' => [
                // nacos server url like https://nacos.hyperf.io, Priority is higher than host:port
                // 'uri' => '',
                'host' => '10.200.15.106',
                'port' => 8848,
                'username' => null,
                'password' => null,
                'guzzle' => [
                    'config' => null,
                ],
                // Only support for nacos v2.
                'grpc' => [
                    'enable' => true,
                    'heartbeat' => 10,
                ],
            ],
        ],

    ],
];
