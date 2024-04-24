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

namespace Mezcalito\FileManagerBundle\Tests\DependencyInjection;

use Mezcalito\FileManagerBundle\DependencyInjection\MezcalitoFileManagerExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\TaggedContainerInterface;

class MezcalitoFileManagerExtensionTest extends TestCase
{
    private TaggedContainerInterface $container;

    public function testLoad(): void
    {
        $extension = new MezcalitoFileManagerExtension();
        $this->container = new ContainerBuilder();

        $extension->load([
            'mezcalito_file_manager' => [
                'storages' => [
                    'local' => [
                        'provider' => 'local',
                        'options' => [
                            'path' => '%kernel.project_dir%/tests/fixtures/storages/local',
                        ],
                    ],
                ],
            ],
        ], $this->container);

        $this->assertTrue($this->container->has('mezcalito_file_manager.provider.local'));
        $this->assertTrue($this->container->has('mezcalito_file_manager.storage.local'));
        $this->assertTrue($this->container->has('mezcalito_file_manager.filesystem.local'));
    }

    public function testUnknownProvider(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Provider "unknown" does not exist.');

        $extension = new MezcalitoFileManagerExtension();
        $this->container = new ContainerBuilder();

        $extension->load([
            'mezcalito_file_manager' => [
                'storages' => [
                    'local' => [
                        'provider' => 'unknown',
                        'options' => [
                            'path' => '%kernel.project_dir%/tests/fixtures/storages/local',
                        ],
                    ],
                ],
            ],
        ], $this->container);
    }
}
