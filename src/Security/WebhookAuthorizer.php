<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

readonly class WebhookAuthorizer
{
    public function __construct(
        private SignatureVerifier $signatureVerifier,
        private LoggerInterface   $logger,
    )
    {
    }

    public function authorize(RequestInterface $request): bool
    {
        $signature = $request->getHeader('x-marketplace-signature');
        $serial = $request->getHeader('x-marketplace-signature-serial');

        if (count($signature) === 0 || count($serial) === 0) {
            $this->logger->warning('received request without signature or serial');
            return false;
        }

        if (strlen($signature[0]) === 0 || strlen($serial[0]) === 0) {
            $this->logger->warning('signature and serial must not be empty');
            return false;
        }

        return $this->signatureVerifier->verifyRequestSignature(
            $request,
            $signature[0],
            $serial[0],
        );
    }
}