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

namespace Mezcalito\FileManagerBundle\Tests\TestApplication\FileManager;

use Mezcalito\FileManagerBundle\Configurator\ConfiguratorInterface;

class CustomConfigurator implements ConfiguratorInterface
{
    public function support(string $storage): bool
    {
        return 'local' === $storage;
    }

    public function overrideConfiguration(array $configuration): array
    {
        $configuration['media_url'] = '/custom/uri-prefix';

        return $configuration;
    }
}
