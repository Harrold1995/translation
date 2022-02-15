<?php declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

//** Environment Variables */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__. '/../../');
$dotenv->load();

$settings = require __DIR__ . '/Settings.php';
$app = new \Slim\App($settings);


require __DIR__ . '/Dependencies.php';
require __DIR__ . '/Routes.php';
require __DIR__ . '/Middleware.php';
require __DIR__ . '/Controllers.php';
