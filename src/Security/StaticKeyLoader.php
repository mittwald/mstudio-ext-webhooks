<?php

namespace Mittwald\MStudio\Webhooks\Security;

readonly class StaticKeyLoader implements KeyLoader
{
    /**
     * @param string[] $keys
     */
    public function __construct(private array $keys)
    {
    }

    public function loadPublicKey(string $serial): string|null
    {
        if (!array_key_exists($serial, $this->keys)) {
            return null;
        }

        return $this->keys[$serial];
    }
}