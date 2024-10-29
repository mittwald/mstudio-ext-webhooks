<?php
namespace Mittwald\MStudio\Webhooks\Dto;

readonly class ExtensionAddedToContextDto
{
    /**
     * @param string $id
     * @param ExtensionInstanceContextDto $context
     * @param string[] $consentedScopes
     * @param ExtensionInstanceStateDto $state
     * @param string $secret
     */
    public function __construct(
        public string                      $id,
        public ExtensionInstanceContextDto $context,
        public array                       $consentedScopes,
        public ExtensionInstanceStateDto   $state,
        public string                      $secret,
    )
    {

    }
}