<?php declare(strict_types=1);

$container = $app->getContainer();

$container['TestController'] = function ($container) {
    return new \App\Controllers\TestController($container);
 };

 $container['FatPluginController'] = function ($container) {
    return new \App\Controllers\FatPluginController($container);
 };


