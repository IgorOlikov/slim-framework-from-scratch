<?php


use Framework\Container\Di\Container;
use Framework\Container\Di\ContainerBuilder;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';


$cli = new Application('Console');


$definitions = require_once __DIR__ . '/../src/Config/definitions.php';

$container = new Container($definitions);

$commands = $container->get('config')['console']['commands'];

foreach ($commands as $class) {
    $command = $container->get($class);
    $cli->add($command);
}

$cli->run();