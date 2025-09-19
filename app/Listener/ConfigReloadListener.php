<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\ConfigCenter\Event\ConfigChanged;
use Psr\Container\ContainerInterface;
use Hyperf\Server\ServerManager;

class ConfigReloadListener implements ListenerInterface
{

    public function __construct(private ContainerInterface $container)
    {
    }

    public function listen(): array
    {
        return [
            ConfigChanged::class,
        ];
    }

    public function process(object $event): void
    {
        var_dump("配置变更",$event);
        // 遍历你所有 server name
        foreach (['http', 'tcp', 'ws'] as $name) {
            $serverInfo = ServerManager::get($name);
            if (is_array($serverInfo) && isset($serverInfo['server']) && $serverInfo['server'] instanceof \Swoole\Server) {
                $result = $serverInfo['server']->reload();
                var_dump("配置变更,{$name}服务重启结果",$result);
            }
        }
    }
}