<?php

namespace Mittwald\MStudio\Webhooks\Security;

/**
 * Interface definition for loading webhook verification public keys.
 */
interface KeyLoader
{
    /**
     * Loads a public key by its key serial number. Implementations should
     * return null when no key with the given serial number is found.
     *
     * @param string $serial Key serial number
     * @return string|null Key in base64 encoding, or `null` when no key with the given serial number exists.
     */
    public function loadPublicKey(string $serial): string|null;
}