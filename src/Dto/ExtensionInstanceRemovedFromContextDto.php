<?php
namespace Mittwald\MStudio\Webhooks\Dto;

readonly class ExtensionInstanceRemovedFromContextDto
{
    /**
     * @param string $id
     * @param ExtensionInstanceContextDto $context
     * @param string[] $consentedScopes
     * @param ExtensionInstanceStateDto $state
     */
    public function __construct(
        public string                      $id,
        public ExtensionInstanceContextDto $context,
        public array                       $consentedScopes,
        public ExtensionInstanceStateDto   $state,
    )
    {

    }
}