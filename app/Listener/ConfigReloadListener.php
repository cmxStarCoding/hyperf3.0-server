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
        $server = ServerManager::get('http');
        if ($server) {
            $server->reload();
        }
    }
}