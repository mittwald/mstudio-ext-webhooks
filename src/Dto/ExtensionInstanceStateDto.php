<?php
namespace Mittwald\MStudio\Webhooks\Dto;

readonly class ExtensionInstanceStateDto
{
    public function __construct(public bool $enabled)
    {

    }
}