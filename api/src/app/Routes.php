<?php declare(strict_types=1);


// $app->get('/', \App\Controllers\Test\TestController::class .':index');
$app->get('/', 'TestController:index');


$app->group('/v1', function () use ($app) {
    $app->group('/test', function () use ($app) {
        $app->get('/', 'TestController:index');
        $app->get('/page/[{id}]', 'TestController:dynamicPage');
        $app->get('/relationship', 'TestController:dbCheck');
        $app->get('/playground', 'TestController:playGround');

    });
    $app->group('/plugin', function () use ($app) {
        $app->post('/fat', 'FatPluginController:searchDocument');
        $app->post('/fat/view', 'FatPluginController:documentCriteria');
        $app->post('/fat/details', 'FatPluginController:documentDetails');
        $app->post('/fat/process/link', 'FatPluginController:processLink');
        $app->post('/fat/search/criteria', 'FatPluginController:searchCriteria');

    });
});




  
