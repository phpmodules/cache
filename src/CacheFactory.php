<?php

namespace PHPModules\Cache;

use Predis\Client;
use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Simple\ApcuCache;
use Symfony\Component\Cache\Simple\ArrayCache;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Simple\MemcachedCache;
use Symfony\Component\Cache\Simple\RedisCache;

class CacheFactory
{
    public function __invoke(ContainerInterface $container):CacheInterface
    {
        $cacheConfig = $container->get('config')['cache'];
        $adapter = $cacheConfig['adapter'];

        if ($adapter === FilesystemCache::class) {
            return new FilesystemCache(
                $cacheConfig['namespace'],
                $cacheConfig['lifetime'],
                $cacheConfig['directory']
            );
        }

        if ($adapter === RedisCache::class) {
            $client = $container->get(Client::class);
            return new RedisCache($client, $cacheConfig['namespace'], $cacheConfig['lifetime']);
        }

        if ($adapter === ArrayCache::class) {
            return new ArrayCache($cacheConfig['lifetime']);
        }

        if ($adapter === ApcuCache::class) {
            return new ApcuCache($cacheConfig['namespace'], $cacheConfig['lifetime']);
        }

        if ($adapter === MemcachedCache::class) {
            $memcachedClient = $container->get(\Memcached::class);
            return new MemcachedCache($memcachedClient, $cacheConfig['namespace'], $cacheConfig['lifetime']);
        }

        return new $adapter;
    }
}
