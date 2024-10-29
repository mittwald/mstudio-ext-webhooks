<?php
namespace Mittwald\MStudio\Webhooks\Security;

use Psr\Http\Message\RequestInterface;

class SignatureVerifier
{
    public function __construct(private readonly KeyLoader $keyLoader)
    {
    }

    /**
     * @param RequestInterface $request
     * @param non-empty-string $signature
     * @param non-empty-string $serial
     * @return bool
     * @throws \SodiumException
     */
    public function verifyRequestSignature(RequestInterface $request, string $signature, string $serial): bool
    {
        $key = $this->keyLoader->loadPublicKey($serial);
        if ($key === null) {
            return false;
        }

        $binSignature = base64_decode($signature);
        $binKey = base64_decode($key);

        if (empty($binSignature) || empty($binKey)) {
            throw new \InvalidArgumentException("signature and key must be in valid base64 encoding");
        }

        return sodium_crypto_sign_verify_detached(
            $binSignature,
            $request->getBody()->getContents(),
            $binKey,
        );
    }
}