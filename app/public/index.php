<?php

use App\Http\Controller\IndexController;
use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Framework\Container\Bridge\Bridge;
use Framework\Container\Di\Container;
use Framework\Core\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';


$definitions = [
    //ServiceInterface::class => create(TestService::class)->constructor('ivan', 'ivanov'),

    //ServiceInterface::class => new TestService('ivan', 'ivanov'),

    ServiceInterface::class => factory(function () {
        return new TestService('ivan', 'ivanov');
    }),

];


$container = Container::create($definitions);
$app = Bridge::create($container);


$app->addErrorMiddleware(true, true, true);

$app->get('/', [IndexController::class, 'index']);


$app->run();

