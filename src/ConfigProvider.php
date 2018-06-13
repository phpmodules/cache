<?php

namespace PHPModules\Cache;

use Psr\SimpleCache\CacheInterface;

class ConfigProvider
{

    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'aliases' => [
                'cache' => CacheInterface::class,
            ],
            'factories'  => [
                CacheInterface::class => CacheFactory::class,
            ],
        ];
    }
}
