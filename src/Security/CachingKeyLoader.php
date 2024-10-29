<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Psr\Cache\CacheItemPoolInterface;

class CachingKeyLoader implements KeyLoader
{
    private KeyLoader $inner;
    private CacheItemPoolInterface $cache;

    public function __construct(KeyLoader $inner, CacheItemPoolInterface $cache)
    {
        $this->inner = $inner;
        $this->cache = $cache;
    }

    public function loadPublicKey(string $serial): string|null
    {
        $cacheKey = "mstudio_public_key_$serial";

        $result = $this->cache->getItem($cacheKey);
        if ($result->isHit()) {
            return $result->get();
        }

        $key = $this->inner->loadPublicKey($serial);
        $result->set($key);
        $this->cache->save($result);

        return $key;
    }

}