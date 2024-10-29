<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Mittwald\MStudio\Webhooks\Security\SignatureVerifier;
use Mittwald\MStudio\Webhooks\Security\WebhookAuthorizer;
use Nyholm\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\isFalse;
use function PHPUnit\Framework\isTrue;
use function PHPUnit\Framework\once;

#[CoversClass(WebhookAuthorizer::class)]
class WebhookAuthorizerTest extends TestCase
{
    #[Test]
    public function shouldFailWhenRequestDoesNotIncludeSignature()
    {
        $sut = new WebhookAuthorizer(
            $this->createStub(SignatureVerifier::class),
            new NullLogger(),
        );
        $req = new Request(method: "POST", uri: "/webhook");
        $res = $sut->authorize($req);

        assertThat($res, isFalse());
    }

    #[Test]
    public function shouldVerifySignatureWhenPresentInRequest()
    {
        $req = (new Request(method: "POST", uri: "/webhook"))
            ->withAddedHeader('x-marketplace-signature', 'test-signature')
            ->withAddedHeader('x-marketplace-signature-serial', 'test-serial');

        $verifier = $this->createMock(SignatureVerifier::class);
        $verifier
            ->expects(once())
            ->method('verifyRequestSignature')
            ->with(
                $req,
                'test-signature',
                'test-serial',
            )
            ->willReturn(true);

        $sut = new WebhookAuthorizer($verifier, new NullLogger());
        $res = $sut->authorize($req);

        assertThat($res, isTrue());
    }
}