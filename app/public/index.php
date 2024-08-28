<?php

use App\Http\Controller\IndexController;
use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Framework\Container\Bridge\Bridge;
use Framework\Container\Di\Container;
use Framework\Core\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';


$definitions = require_once __DIR__ . '/../src/Config/definitions.php';



$container = Container::create($definitions);

$app = Bridge::create($container);


$app->addErrorMiddleware(true, true, true);

$app->get('/', [IndexController::class, 'index']);


$app->run();

