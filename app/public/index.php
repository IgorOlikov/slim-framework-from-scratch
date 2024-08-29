<?php

use App\Http\Controller\ApiController;
use App\Http\Controller\WebController;
use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Framework\Container\Bridge\Bridge;
use Framework\Container\Di\Container;
use Framework\Core\Factory\AppFactory;
use Framework\Core\Routing\RouteCollectorProxy;

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
$app->group('', function (RouteCollectorProxy $proxy) {
   $proxy->get('/', [WebController::class, 'index']);
});


//api routes
$app->group('/api', function (RouteCollectorProxy $proxy) {
    $proxy->get('/home', [ApiController::class, 'index']);
});


// run app
$app->run();

