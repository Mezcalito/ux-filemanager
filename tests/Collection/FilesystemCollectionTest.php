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

namespace Mezcalito\FileManagerBundle\Tests\Collection;

use Mezcalito\FileManagerBundle\Collection\FilesystemCollection;
use Mezcalito\FileManagerBundle\Filesystem\Filesystem;
use Mezcalito\FileManagerBundle\Filesystem\Storage;
use Mezcalito\FileManagerBundle\Provider\LocalFilesystemProvider;
use PHPUnit\Framework\TestCase;

class FilesystemCollectionTest extends TestCase
{
    public function testGet(): void
    {
        $filesystem = new Filesystem(new Storage(new LocalFilesystemProvider('/tmp')));
        $collection = new FilesystemCollection(['local' => $filesystem]);

        $this->assertEquals($collection->get('local'), $filesystem);
    }

    public function testGetUnknownFilesystem(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Filesystem "unknown" not found.');

        $filesystem = new Filesystem(new Storage(new LocalFilesystemProvider('/tmp')));
        $collection = new FilesystemCollection(['local' => $filesystem]);

        $this->assertEquals($collection->get('unknown'), $filesystem);
    }
}
