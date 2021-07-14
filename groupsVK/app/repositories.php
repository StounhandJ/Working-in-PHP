<?php
declare(strict_types=1);

use App\Domain\Group\GroupRepository;
use App\Infrastructure\Persistence\Group\InMemoryGroupRepository;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        GroupRepository::class => \DI\autowire(InMemoryGroupRepository::class),
    ]);
};
