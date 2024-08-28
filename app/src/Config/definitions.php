<?php


use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Framework\Psr\Container\ContainerInterface;
use function App\env;

return [

    ServiceInterface::class => factory(function () {
        return new TestService('ivan', 'ivanov');
    }),


    EntityManagerInterface::class => static function (ContainerInterface $container): EntityManagerInterface {

        $doctrineSettings = $container->get('config')['doctrine'];

        $ormConfig = ORMSetup::createAttributeMetadataConfiguration(
            $doctrineSettings['entities_dir'],
            $doctrineSettings['dev_mode'],
            $doctrineSettings['proxy_dir'],
            $doctrineSettings['cache_dir']
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
            'dev_mode' => env('APP_DEBUG'),
            'cache_dir' => __DIR__ . '/../../var/cache/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../../var/cache/doctrine/proxy',
            'connection' => [
                'driver' => 'pdo_pgsql',
                'host' => env('DB_HOST'),
                'user' => env('DB_USER'),
                'password' => env('DB_PASSWORD'),
                'dbname' => env('DB_NAME'),
                'charset' => 'urf-8'
            ],
            'entities_dir' => __DIR__ . '/../Http/Entity'
        ],
    ],



];