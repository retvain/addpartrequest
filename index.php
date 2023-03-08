<?php

declare(strict_types=1);

use App\Generator;

require_once 'vendor/autoload.php';

const ROOT_DIR = __DIR__;

$data = json_decode(file_get_contents("php://input"), true)['data'] ?? null;

if (empty($data)) {
    throw new Exception("Отсутствуют данные");
}


$generator = new Generator($data);
$content = $generator->generateRequest();

$absoluteFilePath = \App\Saver::saveRequestAddParticipants($content);
\Voskhod\TechExtensions\Common\File::download($absoluteFilePath);
