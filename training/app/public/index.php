<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use ManualConverter\Converter;
use ManualConverter\InputException;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($path !== '/convert') {
    http_response_code(404);
    echo 'Not Found';
    return;
}

$xml = (string) file_get_contents('php://input');

$converter = new Converter();
try {
    $html = $converter->toHtml($xml);
} catch (InputException $e) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=UTF-8');
    echo $e->getMessage();
    return;
}

if ($html === null) {
    http_response_code(400);
    header('Content-Type: text/plain; charset=UTF-8');
    echo implode("\n", $converter->errors);
    return;
}

header('Content-Type: text/html; charset=UTF-8');
echo $html;
