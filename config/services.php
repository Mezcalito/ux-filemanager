<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Mezcalito\FileManagerBundle\Collection\FilesystemCollection;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(FilesystemCollection::class)
            ->args([
                tagged_iterator('mezcalito_file_manager.filesystem', indexAttribute: 'name')
            ])
    ;
};