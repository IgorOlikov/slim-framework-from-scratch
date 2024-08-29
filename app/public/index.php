<?php

use App\Http\Controller\IndexController;
use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Framework\Container\Bridge\Bridge;
use Framework\Container\Di\Container;
use Framework\Core\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';



// container bindings
$definitions = require_once __DIR__ . '/../src/Config/definitions.php';

//build container
$container = Container::create($definitions);

//create app
$app = Bridge::create($container);


// main middlewares
$app->addErrorMiddleware(true, true, true);


// web routes
$app->get('/', [IndexController::class, 'index']);


//api routes


// run app
$app->run();

