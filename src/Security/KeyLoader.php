<?php

namespace Mittwald\MStudio\Webhooks\Security;

interface KeyLoader
{
    public function loadPublicKey(string $serial): string|null;
}