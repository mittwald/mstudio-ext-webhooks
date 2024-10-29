<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

/**
 * Authorizes webhook requests based on the signature in their headers.
 */
readonly class WebhookAuthorizer
{
    private const HEADER_SIGNATURE = "x-marketplace-signature";
    private const HEADER_SERIAL = "x-marketplace-signature-serial";

    private SignatureVerifier $signatureVerifier;
    private LoggerInterface $logger;

    public function __construct(
        SignatureVerifier $signatureVerifier,
        LoggerInterface   $logger,
    )
    {
        $this->signatureVerifier = $signatureVerifier;
        $this->logger            = $logger;
    }

    /**
     * Verifies if the given request has a valid webhook signature.
     *
     * @param RequestInterface $request The request to verify.
     * @return bool true if the request has a valid signature, otherwise false.
     */
    public function authorize(RequestInterface $request): bool
    {
        $signature = $request->getHeader(self::HEADER_SIGNATURE);
        $serial    = $request->getHeader(self::HEADER_SERIAL);

        if (count($signature) === 0 || count($serial) === 0) {
            $this->logger->warning('received request without signature or serial');
            return false;
        }

        if (strlen($signature[0]) === 0 || strlen($serial[0]) === 0) {
            $this->logger->warning('signature and serial must not be empty');
            return false;
        }

        try {
            return $this->signatureVerifier->verifyRequestSignature(
                $request,
                $signature[0],
                $serial[0],
            );
        } catch (BadSignatureException $err) {
            $this->logger->error('bad request signature', ['err' => $err]);
            return false;
        }
    }
}