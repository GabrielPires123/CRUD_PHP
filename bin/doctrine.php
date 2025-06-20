<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Helper\EntityManagerCreator;

require_once __DIR__ . '/../vendor/autoload.php';


$entityManager = EntityManagerCreator::createEntityManager();

$commands = [

];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);