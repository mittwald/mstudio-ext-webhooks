<?php
namespace Mittwald\MStudio\Webhooks\Security;

use Exception;
use Psr\Http\Message\RequestInterface;
use SodiumException;

/**
 * Service class for verifying the request signature of mStudio webhook requests.
 */
class SignatureVerifier
{
    private readonly KeyLoader $keyLoader;

    public function __construct(KeyLoader $keyLoader)
    {
        $this->keyLoader = $keyLoader;
    }

    /**
     * Verifies if the signature of a request is valid.
     *
     * @param RequestInterface $request The raw request object; the signature will be verified using the request body.
     * @param non-empty-string $signature The request signature, in base64 encoding
     * @param non-empty-string $serial The signature key serial
     * @return bool `true` if the request signature is valid, otherwise `false`
     * @throws BadSignatureException
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

        try {
            return sodium_crypto_sign_verify_detached(
                $binSignature,
                $request->getBody()->getContents(),
                $binKey,
            );
        } catch (SodiumException $err) {
            throw new BadSignatureException('error while verifying request signature', previous: $err);
        }
    }
}