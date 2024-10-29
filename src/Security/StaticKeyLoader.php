<?php

namespace Mittwald\MStudio\Webhooks\Security;

/**
 * Implements the KeyLoader interface with static keys.
 *
 * NOTE: This is intended for use in unit testing, ONLY.
 *
 * @internal
 */
readonly class StaticKeyLoader implements KeyLoader
{
    /**
     * @param string[] $keys An associative array of base64-encoded keys, using the key serials as key.
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