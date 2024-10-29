<?php
namespace Mittwald\MStudio\Webhooks\Dto;

readonly class ExtensionInstanceContextDto
{
    public function __construct(
        public string $id,
        public string $kind,
    )
    {
    }
}