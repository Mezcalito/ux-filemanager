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
use Mezcalito\FileManagerBundle\Provider\ProviderInterface;
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

        $resolver->setDefault('media_url', '');
        $resolver->setAllowedTypes('media_url', 'string');

        $resolver->setDefault('ignore_dot_files', true);
        $resolver->setAllowedTypes('ignore_dot_files', 'bool');
    }

    public function create(array $options): ?ProviderInterface
    {
        return new LocalFilesystemProvider($options['path'], $options['media_url'], $options['ignore_dot_files']);
    }
}
