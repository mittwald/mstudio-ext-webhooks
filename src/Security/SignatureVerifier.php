<?php
namespace Mittwald\MStudio\Webhooks\Security;

use Psr\Http\Message\RequestInterface;

class SignatureVerifier
{
    public function __construct(private readonly KeyLoader $keyLoader)
    {
    }

    public function verifyRequestSignature(RequestInterface $request, string $signature, string $serial): bool
    {
        $key = $this->keyLoader->loadPublicKey($serial);
        if ($key === null) {
            return false;
        }

        return sodium_crypto_sign_verify_detached(
            base64_decode($signature),
            $request->getBody()->getContents(),
            base64_decode($key),
        );
    }
}