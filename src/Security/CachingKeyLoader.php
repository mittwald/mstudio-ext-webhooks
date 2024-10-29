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
            $value = $result->get();
            if (is_string($value) || is_null($value)) {
                return $value;
            }
        }

        $key = $this->inner->loadPublicKey($serial);
        $result->set($key);
        $this->cache->save($result);

        return $key;
    }

}