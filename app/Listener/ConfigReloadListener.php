<?php

declare(strict_types=1);

namespace App\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\ConfigCenter\Event\ConfigChanged;
use Psr\Container\ContainerInterface;
use Swoole\Server;

class ConfigReloadListener implements ListenerInterface
{
    protected Server $server;

    public function __construct(ContainerInterface $container)
    {
        $this->server = $container->get(Server::class);
    }

    public function listen(): array
    {
        return [
            ConfigChanged::class,
        ];
    }

    public function process(object $event): void
    {
        if (! $event instanceof ConfigChanged) {
            return;
        }

        // 这里可以打印变化的配置
        var_dump("配置变更",$event);

        // 热重载 Worker，不断开连接
        $this->server->reload();
    }
}