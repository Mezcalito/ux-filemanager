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

use Mezcalito\FileManagerBundle\Collection\FilesystemCollection;
use Mezcalito\FileManagerBundle\Filesystem\Filesystem;
use Mezcalito\FileManagerBundle\Filesystem\Storage;
use Mezcalito\FileManagerBundle\Provider\Factory\LocalFilesystemProviderFactory;
use Mezcalito\FileManagerBundle\Twig\Components\FileManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MezcalitoFileManagerExtension extends Extension
{
    /** @var LocalFilesystemProviderFactory[] */
    private readonly array $providerFactories;

    public function __construct()
    {
        $this->providerFactories = [
            new LocalFilesystemProviderFactory(),
        ];
    }

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.php');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        foreach ($config['storages'] as $storageName => $storageConfig) {
            $providerDefinition = null;
            foreach ($this->providerFactories as $factory) {
                if ($factory->support($storageConfig['provider'])) {
                    $resolver = new OptionsResolver();
                    $factory->configureResolver($resolver);

                    $providerDefinition = $factory->createDefinition($resolver->resolve($storageConfig['options']));
                }
            }

            if (null === $providerDefinition) {
                throw new \LogicException('Provider "'.$storageConfig['provider'].'" does not exist.');
            }

            $container->setDefinition('mezcalito_file_manager.provider.'.$storageName, $providerDefinition);

            $container->register('mezcalito_file_manager.storage.'.$storageName, Storage::class)
                ->setArguments([
                    new Reference('mezcalito_file_manager.provider.'.$storageName),
                ]);

            $container->register('mezcalito_file_manager.filesystem.'.$storageName, Filesystem::class)
                ->addTag('mezcalito_file_manager.filesystem', ['name' => $storageName])
                ->setArguments([
                    new Reference('mezcalito_file_manager.storage.'.$storageName),
                ]);
        }

        $container->register(FileManager::class)
            ->setAutoconfigured(true);
    }
}
