<?php

/*
 * This file is part of the Mezcalito UX FileManager project.
 *
 * (c) Mezcalito <dev@mezcalito.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Mezcalito\FileManagerBundle\DependencyInjection\Compiler;

use Mezcalito\FileManagerBundle\Factory\FilesystemFactory;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfiguratorPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        $listConfigurators = $this->findAndSortTaggedServices('mezcalito_file_manager.configurator', $container);

        $container
            ->getDefinition(FilesystemFactory::class)
            ->setArgument(1, new IteratorArgument($listConfigurators));
    }
}
