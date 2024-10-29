<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Psr\Cache\CacheItemPoolInterface;

/**
 * CachingKeyLoader is a helper class that can be used to decorate a key loader
 * with a PSR-6 compatible cache.
 */
class CachingKeyLoader implements KeyLoader
{
    private KeyLoader $inner;
    private CacheItemPoolInterface $cache;

    /**
     * @param KeyLoader $inner The key loader to decorate
     * @param CacheItemPoolInterface $cache The cache implementation
     */
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