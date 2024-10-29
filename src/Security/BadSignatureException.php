<?php

namespace Mittwald\MStudio\Webhooks\Security;

use Exception;

/**
 * Describes a request signature so invalid that it caused an error.
 */
class BadSignatureException extends Exception
{

}