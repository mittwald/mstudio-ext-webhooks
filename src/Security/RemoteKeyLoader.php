<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Mittwald\ApiClient\Error\UnexpectedResponseException;
use Mittwald\ApiClient\Generated\V2\Clients\Marketplace\ExtensionGetPublicKey\ExtensionGetPublicKeyRequest;
use Mittwald\ApiClient\MittwaldAPIV2Client;

/**
 * Loads a webhook verification key from the mittwald mStudio API.
 */
readonly class RemoteKeyLoader implements KeyLoader
{
    public function __construct(private MittwaldAPIV2Client $client)
    {
    }

    public function loadPublicKey(string $serial): string|null
    {
        try {
            $req  = new ExtensionGetPublicKeyRequest($serial);
            $resp = $this->client->marketplace()->extensionGetPublicKey($req);

            return $resp->getBody()->getKey();
        } catch (UnexpectedResponseException $err) {
            if ($err->response->getStatusCode() === 404) {
                return null;
            } else {
                throw $err;
            }
        }
    }
}