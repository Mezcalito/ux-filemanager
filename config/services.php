<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Mezcalito\FileManagerBundle\Factory\FilesystemFactory;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(FilesystemFactory::class)
            ->args([
                service('parameter_bag'),
            ])
    ;
};