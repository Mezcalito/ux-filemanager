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

namespace Mezcalito\FileManagerBundle\Factory;

use Mezcalito\FileManagerBundle\Filesystem\Filesystem;
use Mezcalito\FileManagerBundle\Provider\Factory\LocalFilesystemProviderFactory;
use Mezcalito\FileManagerBundle\Provider\Factory\ProviderFactoryInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

readonly class FilesystemFactory
{
    /** @var ProviderFactoryInterface[] */
    private array $providerFactories;

    public function __construct(
        private ContainerBagInterface $params,
    ) {
        $this->providerFactories = [new LocalFilesystemProviderFactory()];
    }

    public function get(string $storage): Filesystem
    {
        try {
            $config = $this->params->get('mezcalito_file_manager.storage_configs.'.$storage);
        } catch (ParameterNotFoundException) {
            throw new \InvalidArgumentException('Storage "'.$storage.'" does not have configuration.');
        }

        $provider = null;
        foreach ($this->providerFactories as $factory) {
            if (!$factory->support($config['provider'])) {
                continue;
            }

            $resolver = new OptionsResolver();
            $factory->configureResolver($resolver);

            $provider = $factory->create($resolver->resolve($config['options']));
            break;
        }

        if (null === $provider) {
            throw new \LogicException('Provider "'.$storage.'" does not exist.');
        }

        return new Filesystem($provider);
    }
}
