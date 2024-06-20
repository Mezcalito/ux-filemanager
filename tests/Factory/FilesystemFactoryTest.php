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

namespace Mezcalito\FileManagerBundle\Tests\Factory;

use Mezcalito\FileManagerBundle\Factory\FilesystemFactory;
use Mezcalito\FileManagerBundle\Filesystem\FilesystemInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FilesystemFactoryTest extends KernelTestCase
{
    public function testGet(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $factory = $container->get(FilesystemFactory::class);
        $filesystem = $factory->get('local');

        $this->assertInstanceOf(FilesystemInterface::class, $filesystem);
    }

    public function testGetUnknownStorage(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Storage "unknown" does not have configuration.');

        $factory = $container->get(FilesystemFactory::class);
        $filesystem = $factory->get('unknown');
    }
}
