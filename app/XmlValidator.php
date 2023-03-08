<?php

declare(strict_types=1);

namespace App;

use Libs\Utils;
use Throwable;

class XmlValidator
{
    public function validateXml(string $xml): bool
    {
        try {
            Utils::validateXmlByXSD($xml,
                ROOT_DIR
                . DIRECTORY_SEPARATOR
                . 'storage'
                . DIRECTORY_SEPARATOR
                . 'xsd'
                . DIRECTORY_SEPARATOR
                . 'iedms_gas_v271-03-2022_addressees.xsd'
            );
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}