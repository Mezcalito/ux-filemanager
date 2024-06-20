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

class MezcalitoFileManagerExtensionTest extends TestCase
{
    public function testLoad(): void
    {
        $extension = new MezcalitoFileManagerExtension();
        $container = new ContainerBuilder();

        $extension->load([
            'mezcalito_file_manager' => [
                'default_media_url' => 'https://media.my-website.com/',
                'storages' => [
                    'local' => [
                        'provider' => 'local',
                        'uri_prefix' => '/images',
                        'options' => [
                            'path' => '%kernel.project_dir%/tests/fixtures/storages/local',
                        ],
                    ],
                ],
            ],
        ], $container);

        $this->assertTrue($container->hasParameter('mezcalito_file_manager.storage_configs.local'));
        $this->assertEquals([
            'provider' => 'local',
            'uri_prefix' => '/images',
            'options' => [
                'path' => '%kernel.project_dir%/tests/fixtures/storages/local',
                'media_url' => 'https://media.my-website.com/images',
            ],
        ], $container->getParameter('mezcalito_file_manager.storage_configs.local'));
    }
}
