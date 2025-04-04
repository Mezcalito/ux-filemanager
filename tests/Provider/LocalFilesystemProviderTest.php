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

namespace Mezcalito\FileManagerBundle\Tests\Provider;

use Mezcalito\FileManagerBundle\Filesystem\Node;
use Mezcalito\FileManagerBundle\Provider\LocalFilesystemProvider;
use PHPUnit\Framework\TestCase;

use function Mezcalito\FileManagerBundle\Tests\create_directory;
use function Mezcalito\FileManagerBundle\Tests\delete_directory;

class LocalFilesystemProviderTest extends TestCase
{
    public const string ROOT = __DIR__.'/test-filesystem-provider';

    protected function setUp(): void
    {
        create_directory(self::ROOT);
    }

    protected function tearDown(): void
    {
        delete_directory(static::ROOT);
    }

    public function testRead(): void
    {
        $adapter = new LocalFilesystemProvider(static::ROOT);
        $adapter->write('path.txt', 'contents');

        $contents = $adapter->read('path.txt');
        $this->assertEquals('contents', $contents);
    }

    public function testWrite(): void
    {
        $adapter = new LocalFilesystemProvider(static::ROOT);

        $adapter->write('/file.txt', 'contents');

        $this->assertFileExists(static::ROOT.'/file.txt');
        $contents = file_get_contents(static::ROOT.'/file.txt');
        $this->assertEquals('contents', $contents);
    }

    public function testDelete(): void
    {
        $adapter = new LocalFilesystemProvider(static::ROOT);
        file_put_contents(static::ROOT.'/file.txt', 'contents');
        $adapter->delete('/file.txt');
        $this->assertFileDoesNotExist(static::ROOT.'/file.txt');
    }

    public function testMove(): void
    {
        $adapter = new LocalFilesystemProvider(static::ROOT);
        $adapter->write('first.txt', 'contents');
        $this->assertFileExists(static::ROOT.'/first.txt');
        $adapter->move('first.txt', 'second.txt');
        $this->assertFileExists(static::ROOT.'/second.txt');
        $this->assertFileDoesNotExist(static::ROOT.'/first.txt');
    }

    public function testCopy(): void
    {
        $adapter = new LocalFilesystemProvider(static::ROOT);
        $adapter->write('first.txt', 'contents');
        $this->assertFileExists(static::ROOT.'/first.txt');
        $adapter->copy('first.txt', 'second.txt');
        $this->assertFileExists(static::ROOT.'/first.txt');
        $this->assertEquals('contents', $adapter->read('/first.txt'));
        $this->assertFileExists(static::ROOT.'/second.txt');
        $this->assertEquals('contents', $adapter->read('/second.txt'));
    }

    public function testListDirectory(): void
    {
        $provider = new LocalFilesystemProvider(self::ROOT);
        $provider->write('directory/filename.txt', 'content');
        $provider->write('filename.txt', 'content');
        $provider->write('.hidden', 'content');

        $contentIterator = $provider->listDirectory('/');
        $contents = iterator_to_array($contentIterator);
        $this->assertCount(2, $contents);
    }

    public function testListDirectoryWithDotFiles(): void
    {
        $provider = new LocalFilesystemProvider(self::ROOT, ignoreDotFiles: false);
        $provider->write('directory/filename.txt', 'content');
        $provider->write('filename.txt', 'content');
        $provider->write('.hidden', 'content');

        $contentIterator = $provider->listDirectory('/');
        $contents = iterator_to_array($contentIterator);
        $this->assertCount(3, $contents);
    }

    public function testNodeInfo(): void
    {
        $provider = new LocalFilesystemProvider(self::ROOT);
        $provider->write('filename.txt', 'content');

        $contentIterator = $provider->listDirectory('/');
        $contents = iterator_to_array($contentIterator);

        /** @var Node $firstNode */
        $firstNode = $contents[0];
        $this->assertEquals('filename.txt', $firstNode->getId());
        $this->assertEquals('filename.txt', $firstNode->getPath());
        $this->assertTrue($firstNode->isFile());
        $this->assertFalse($firstNode->isDir());
    }

    public function testListDirectoryRecursively(): void
    {
        $provider = new LocalFilesystemProvider(self::ROOT);
        $provider->write('directory/filename.txt', 'content');
        $provider->write('filename.txt', 'content');

        $contentIterator = $provider->listDirectory('/', true);
        $contents = iterator_to_array($contentIterator);

        $this->assertCount(3, $contents);
    }

    public function testCreateDirectory(): void
    {
        $provider = new LocalFilesystemProvider(static::ROOT);
        $provider->createDirectory('/directory');
        $this->assertDirectoryExists(self::ROOT.'/directory');
    }

    public function testDeleteDirectory(): void
    {
        $provider = new LocalFilesystemProvider(static::ROOT);
        $provider->createDirectory('/directory');
        $this->assertDirectoryExists(self::ROOT.'/directory');
        $provider->deleteDirectory('/directory');
        $this->assertDirectoryDoesNotExist(self::ROOT.'/directory');
    }

    public function testDeleteDirectoryRecursively(): void
    {
        $provider = new LocalFilesystemProvider(static::ROOT);
        $provider->createDirectory('/directory/child');
        $this->assertDirectoryExists(self::ROOT.'/directory/child');
        $provider->deleteDirectory('/directory');
        $this->assertDirectoryDoesNotExist(self::ROOT.'/directory');
    }

    public function testSearch(): void
    {
        $provider = new LocalFilesystemProvider(self::ROOT);
        $provider->write('directory/filename.txt', 'content');
        $provider->write('filename.txt', 'content');
        $provider->write('test.txt', 'content');

        $contentIterator = $provider->search('test');
        $contents = iterator_to_array($contentIterator);

        $this->assertCount(1, $contents);
    }
}
