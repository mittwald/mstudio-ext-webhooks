<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Nyholm\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertThat;
use function PHPUnit\Framework\isFalse;
use function PHPUnit\Framework\isTrue;

#[CoversClass(SignatureVerifier::class)]
class SignatureVerifierTest extends TestCase
{
    #[Test]
    public function shouldVerifySignatureAgainstKey()
    {
        $req = new Request(
            method: "POST",
            uri: "/webhook",
            body: '{"apiVersion":"v1","id":"4f9bed5a-ea27-4b31-8cb4-5fd8f1d35cf7","kind":"ExtensionAddedToContext","request":{"id":"01922b26-f489-74bc-98a4-b2a50584864d","createdAt":"2024-09-25T21:47:06.249313263Z","target":{"method":"POST","url":"https://host.docker.internal:8000/mstudiov1/extension-added"}},"context":{"id":"63350a60-2a95-4833-848f-2f653d0f0ac9","kind":"project"},"consentedScopes":["some:foo","scope:bar"],"state":{"enabled":true},"meta":{"extensionId":"fc8ecde1-e69c-4559-b01b-e655d7d0290a","contributorId":"901c7cd0-d869-4290-ab55-867b63227896"},"secret":"new secret"}'
        );
        $sut = new SignatureVerifier(new StaticKeyLoader([
            "a73d94dd-bf8a-4a74-967b-f7a0dd1de71f" => '9WIYLTuOGgw9axq3WK8irH0V0I/94XuH5UArDo600ys=',
        ]));

        $valid = $sut->verifyRequestSignature(
            $req,
            "GQTxbuw7mXr/uimnAx5BzWJp0JnDPQGtv3fjqa36L6Q59guTyJ2TNxlFX7ajjj6C5UuGXFAwhxdZ/w2Fsyq7BQ==",
            "a73d94dd-bf8a-4a74-967b-f7a0dd1de71f",
        );

        assertThat($valid, isTrue());
    }

    #[Test]
    public function shouldFailWhenSignatureDoesNotMatch()
    {
        $req = new Request(
            method: "POST",
            uri: "/webhook",
            body: '{"apiVersion":"v1","id":"5f9bed5a-ea27-4b31-8cb4-5fd8f1d35cf7","kind":"ExtensionAddedToContext","request":{"id":"01922b26-f489-74bc-98a4-b2a50584864d","createdAt":"2024-09-25T21:47:06.249313263Z","target":{"method":"POST","url":"https://host.docker.internal:8000/mstudiov1/extension-added"}},"context":{"id":"63350a60-2a95-4833-848f-2f653d0f0ac9","kind":"project"},"consentedScopes":["some:foo","scope:bar"],"state":{"enabled":true},"meta":{"extensionId":"fc8ecde1-e69c-4559-b01b-e655d7d0290a","contributorId":"901c7cd0-d869-4290-ab55-867b63227896"},"secret":"new secret"}'
        );
        $sut = new SignatureVerifier(new StaticKeyLoader([
            "a73d94dd-bf8a-4a74-967b-f7a0dd1de71f" => '9WIYLTuOGgw9axq3WK8irH0V0I/94XuH5UArDo600ys=',
        ]));

        $valid = $sut->verifyRequestSignature(
            $req,
            "GQTxbuw7mXr/uimnAx5BzWJp0JnDPQGtv3fjqa36L6Q59guTyJ2TNxlFX7ajjj6C5UuGXFAwhxdZ/w2Fsyq7BQ==",
            "a73d94dd-bf8a-4a74-967b-f7a0dd1de71f",
        );

        assertThat($valid, isFalse());
    }

    #[Test]
    public function shouldFailWhenKeyCannotBeFound()
    {
        $req = new Request(
            method: "POST",
            uri: "/webhook",
            body: '{"apiVersion":"v1","id":"4f9bed5a-ea27-4b31-8cb4-5fd8f1d35cf7","kind":"ExtensionAddedToContext","request":{"id":"01922b26-f489-74bc-98a4-b2a50584864d","createdAt":"2024-09-25T21:47:06.249313263Z","target":{"method":"POST","url":"https://host.docker.internal:8000/mstudiov1/extension-added"}},"context":{"id":"63350a60-2a95-4833-848f-2f653d0f0ac9","kind":"project"},"consentedScopes":["some:foo","scope:bar"],"state":{"enabled":true},"meta":{"extensionId":"fc8ecde1-e69c-4559-b01b-e655d7d0290a","contributorId":"901c7cd0-d869-4290-ab55-867b63227896"},"secret":"new secret"}'
        );
        $sut = new SignatureVerifier(new StaticKeyLoader([]));

        $valid = $sut->verifyRequestSignature(
            $req,
            "GQTxbuw7mXr/uimnAx5BzWJp0JnDPQGtv3fjqa36L6Q59guTyJ2TNxlFX7ajjj6C5UuGXFAwhxdZ/w2Fsyq7BQ==",
            "a73d94dd-bf8a-4a74-967b-f7a0dd1de71f",
        );

        assertThat($valid, isFalse());
    }
}