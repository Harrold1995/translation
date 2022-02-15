<?php declare(strict_types=1);

use Slim\Http\Request;
use Slim\Http\Response;

$container = $app->getContainer();
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
   return $capsule;
};

$container['upload_directory'] = __DIR__ . '/uploads';

$container['errorHandler'] = function ($container) {
   return function (Request $request, Response $response, \Exception $exception) use ($container) {
       //Format of exception to return
       $statusCode = 500;
        if (is_int($exception->getCode()) && $exception->getCode() >= 400 && $exception->getCode() <= 599) {
            $statusCode = $exception->getCode();
        }
        $message = $exception->getMessage();

        //** Customized Error */
        $errorCode = $exception->errorInfo[1];
        if($errorCode == 1062){
            $message = 'Duplicate Entry!';
            // $message = $exception->getMessage();
        } else if ($errorCode == 1452) {
            $message = 'Integrity constraint!';
        }
        
        $className = new \ReflectionClass(get_class($exception));
        $data = [
            'message' => $message,
            //'class' => $className->getShortName(),
            'detailed_message' => $exception->getMessage(),
            'status' => 'error',
            'code' => $statusCode
        ];
        $body = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        return $response
                ->withStatus($statusCode)
                ->withHeader('Content-type', 'application/problem+json')
                ->write($body);
   };
};