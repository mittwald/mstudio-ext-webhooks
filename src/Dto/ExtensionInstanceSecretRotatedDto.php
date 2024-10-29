<?php
namespace Mittwald\MStudio\Webhooks\Dto;

readonly class ExtensionInstanceSecretRotatedDto
{

    public function __construct(
        public string                      $id,
        public ExtensionInstanceContextDto $context,
        public string                      $secret,
    )
    {

    }
}