<?php

use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Framework\Psr\Container\ContainerInterface;
use Framework\Psr\Http\Factory\ResponseFactoryInterface;
use Framework\Psr\Http\Factory\ServerRequestFactoryInterface;
use Framework\Psr7\Factory\ResponseFactory;
use Framework\Psr7\Factory\ServerRequestFactory;
use Slim\Csrf\Guard;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Extra\Intl\IntlExtension;
use Twig\Loader\FilesystemLoader;
use function App\env;

return [

    ServerRequestFactoryInterface::class => static fn(): ServerRequestFactoryInterface => new ServerRequestFactory(),
    ResponseFactoryInterface::class => static fn(): ResponseFactoryInterface => new ResponseFactory(),

    ServiceInterface::class => factory(function () {
        return new TestService('ivan', 'ivanov');
    }),


    /** custom psr7 not compatible with official php-fig psr7 !!!
    'csrf' => static function (ResponseFactoryInterface $responseFactory) {
        return new Guard($responseFactory, ?!);
    },
    **/

    // twig template renderer
    Environment::class => static function (ContainerInterface $container): Environment {
        $config = $container->get('config')['twig'];

        $loader = new FilesystemLoader();

        foreach ($config['template_dirs'] as $alias => $dir) {
            $loader->addPath($dir, $alias);
        }

        $environment = new Environment(
            $loader,
            [
                'cache' => $config['debug'] ? false : $config['cache_dir'],
                'debug' => $config['debug'],
                'strict_variables' => $config['debug'],
                'auto_reload' => $config['debug'],
            ]
        );

        if ($config['debug']) {
            $environment->addExtension(new DebugExtension());
        }
        $environment->addExtension(new IntlExtension());

        // custom extensions
        foreach ($config['extensions'] as $class) {
            $extension = $container->get($class);
            $environment->addExtension($extension);
        }

        return $environment;
    },

    EntityManagerInterface::class => static function (ContainerInterface $container): EntityManagerInterface {
        $doctrineSettings = $container->get('config')['doctrine'];

        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $doctrineSettings['entities_dir'],
            $doctrineSettings['dev_mode'],
            $doctrineSettings['proxy_dir'],
            $doctrineSettings['cache_dir'] !== null
                ? new FilesystemAdapter('', 0, $doctrineSettings['cache_dir'])
                : new ArrayAdapter()
        );

        return new EntityManager(
            DriverManager::getConnection($doctrineSettings['connection'], $ormConfig),
            $ormConfig
        );
    },

    Connection::class => static function (ContainerInterface $container) {
        $entityManager = $container->get(EntityManagerInterface::class);
        /**
         * @var EntityManagerInterface $entityManager
         */
        return $entityManager->getConnection();
    },

    'config' => [
        'doctrine' => [
            'dev_mode' => (bool)env('APP_DEBUG'),
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => env('POSTGRES_HOST'),
                'user' => env('POSTGRES_USER'),
                'password' => env('POSTGRES_PASSWORD'),
                'dbname' => env('POSTGRES_DB'),
                'charset' => 'utf-8'
            ],
            'entities_dir' =>  [
                __DIR__ . '/../Http/Entity'
            ],
        ],
        'twig' => [
            'debug' => (bool)env('APP_DEBUG'),
            'template_dirs' => [
                FilesystemLoader::MAIN_NAMESPACE => __DIR__ . '/../Templates',
            ],
            'cache_dir' => __DIR__ . '/../../var/cache/twig',
            'extensions' => [

            ],

        ],
    ],



];