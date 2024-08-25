<?php

use App\Http\Controller\IndexController;
use Framework\Core\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';



$app = AppFactory::create();

$app->get('/', [IndexController::class, 'index']);


$app->run();

