<?php
declare(strict_types=1);

require_once __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . 'StoryController.php';

$controller = new StoryController();
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $controller->store();
} else {
    $controller->index();
}
