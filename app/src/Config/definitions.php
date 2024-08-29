<?php


use App\Http\Services\Interfaces\ServiceInterface;
use App\Http\Services\TestService;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Framework\Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
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
            'dev_mode' => env('APP_DEBUG'),
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
    ],



];