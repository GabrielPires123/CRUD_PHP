<?php

namespace Helper;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class EntityManagerCreator
{
    public static function createEntityManager():EntityManager
    {
        $config = ORMSetup::createAttributeMetadataConfiguration
        (
            [__DIR__ . '/..'],
            isDevMode: true
        );
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => __DIR__ . '/../../db.sqlite',
        ], $config);

        return new EntityManager($connection, $config);

    }

}