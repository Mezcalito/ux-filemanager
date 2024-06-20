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

namespace Mezcalito\FileManagerBundle\DependencyInjection;

use Mezcalito\FileManagerBundle\Twig\Components\Content;
use Mezcalito\FileManagerBundle\Twig\Components\File;
use Mezcalito\FileManagerBundle\Twig\Components\FileManager;
use Mezcalito\FileManagerBundle\Twig\Components\Folder;
use Mezcalito\FileManagerBundle\Twig\Components\Modal;
use Mezcalito\FileManagerBundle\Twig\Components\Sidebar;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class MezcalitoFileManagerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $defaultMediaUrl = rtrim($config['default_media_url'] ?? '', '/');

        foreach ($config['storages'] as $storageName => $storageConfig) {
            $mediaUrl = $storageConfig['media_url'] ?? $defaultMediaUrl;
            $mediaUrl .= rtrim($storageConfig['uri_prefix'] ?? '', '/');
            if ('' !== $mediaUrl) {
                $storageConfig['options']['media_url'] = $mediaUrl;
            }

            $container->setParameter('mezcalito_file_manager.storage_configs.'.$storageName, $storageConfig);
        }

        $container->register(FileManager::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(Sidebar::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(Content::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(File::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(Folder::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(Modal::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);
    }
}
