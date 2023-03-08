<?php

declare(strict_types=1);

namespace App;

use DateTime;
use Voskhod\TechExtensions\Common\Folder;

class Saver
{
    /**
     * @param string $xml
     * @return string
     */
    public static function saveRequestAddParticipants(string $xml): string
    {
        $time = strtotime(date(DateTime::ATOM));
        $datePrefix = date("Y", $time) . '-' . date("m", $time);

        $absoluteDirPath = ROOT_DIR
            . DIRECTORY_SEPARATOR
            . 'storage'
            . DIRECTORY_SEPARATOR
            . 'generated'
            . DIRECTORY_SEPARATOR
            . 'requests'
            . DIRECTORY_SEPARATOR
            . 'addParticipants'
            . DIRECTORY_SEPARATOR
            . $datePrefix
            . DIRECTORY_SEPARATOR;

        Folder::checkOrCreate($absoluteDirPath);

        $absoluteFilePath = $absoluteDirPath . 'passport_' . uniqid() . '.xml';

        file_put_contents($absoluteFilePath, $xml);

        return $absoluteFilePath;
    }
}