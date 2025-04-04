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

use Mezcalito\FileManagerBundle\Configurator\ConfiguratorInterface;
use Mezcalito\FileManagerBundle\Controller\DownloadController;
use Mezcalito\FileManagerBundle\Service\EncryptionService;
use Mezcalito\FileManagerBundle\Twig\Components\Content;
use Mezcalito\FileManagerBundle\Twig\Components\File;
use Mezcalito\FileManagerBundle\Twig\Components\FileSystem;
use Mezcalito\FileManagerBundle\Twig\Components\Folder;
use Mezcalito\FileManagerBundle\Twig\Components\Modal;
use Mezcalito\FileManagerBundle\Twig\Components\Search;
use Mezcalito\FileManagerBundle\Twig\Components\SearchItemResult;
use Mezcalito\FileManagerBundle\Twig\Components\Sidebar;
use Symfony\Component\AssetMapper\AssetMapperInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

class MezcalitoFileManagerExtension extends Extension implements PrependExtensionInterface
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

        $container->registerForAutoconfiguration(ConfiguratorInterface::class)
            ->addTag('mezcalito_file_manager.configurator');

        $container->register(FileSystem::class)
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
            ->setAutoconfigured(true)
            ->setArguments([
                new Reference('translator', ContainerInterface::IGNORE_ON_INVALID_REFERENCE),
            ]);

        $container->register(Search::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(SearchItemResult::class)
            ->setAutowired(true)
            ->setAutoconfigured(true);

        $container->register(EncryptionService::class)
            ->setArguments([
                new Parameter('kernel.secret'),
            ]);

        $container->register(DownloadController::class)
            ->addTag('controller.service_arguments')
            ->setArguments([
                new Reference(EncryptionService::class),
            ]);
    }

    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('twig_component', [
            'defaults' => [
                'Mezcalito\\FileManagerBundle\\Twig\\Components\\' => [
                    'template_directory' => '@MezcalitoFileManager/',
                    'name_prefix' => 'Mezcalito:FileManager',
                ],
            ],
        ]);

        if (!$this->isAssetMapperAvailable($container)) {
            return;
        }

        $container->prependExtensionConfig('framework', [
            'asset_mapper' => [
                'paths' => [
                    __DIR__.'/../../assets/dist' => '@mezcalito/ux-filemanager',
                ],
            ],
        ]);
    }

    private function isAssetMapperAvailable(ContainerBuilder $container): bool
    {
        if (!interface_exists(AssetMapperInterface::class)) {
            return false;
        }

        // check that FrameworkBundle 6.3 or higher is installed
        $bundlesMetadata = $container->getParameter('kernel.bundles_metadata');
        if (!isset($bundlesMetadata['FrameworkBundle'])) {
            return false;
        }

        return is_file($bundlesMetadata['FrameworkBundle']['path'].'/Resources/config/asset_mapper.php');
    }
}
