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

namespace Mezcalito\FileManagerBundle\Provider\Factory;

use Mezcalito\FileManagerBundle\Provider\LocalFilesystemProvider;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalFilesystemProviderFactory implements ProviderFactoryInterface
{
    public function support(string $name): bool
    {
        return 'local' === $name;
    }

    public function configureResolver(OptionsResolver $resolver): void
    {
        $resolver->setRequired('path');
        $resolver->setAllowedTypes('path', 'string');
    }

    public function createDefinition(array $options): ?Definition
    {
        $definition = new Definition(LocalFilesystemProvider::class);
        $definition->setArgument(0, $options['path']);

        return $definition;
    }
}
