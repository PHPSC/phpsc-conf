<?php
namespace PHPSC\Conference\Infra\Errors;

use Lcobucci\ActionMapper2\Errors\DefaultHandler;

/**
 * @author Luís Otávio Cobucci Oblonczyk
 */
class ErrorHandler extends DefaultHandler
{
    /**
     * {@inheritdoc}
     */
    protected function shouldSkipError($severity, $message)
    {
        if ($message === 'json_encode(): type is unsupported, encoded as null') {
            return true;
        }

        return false;
    }
}
