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

namespace Mezcalito\FileManagerBundle\Provider;

use Mezcalito\FileManagerBundle\Exception\IOException;
use Mezcalito\FileManagerBundle\Filesystem\Node;

readonly class LocalFilesystemProvider implements ProviderInterface
{
    private string $rootLocation;

    public function __construct(
        string $location,
        private string $mediaUrl = '',
        private bool $ignoreDotFiles = true,
    ) {
        $this->rootLocation = rtrim($location, '\\/').\DIRECTORY_SEPARATOR;
    }

    public function info(string $id): Node
    {
        $location = $this->prefixPath($id);
        $fileInfo = new \SplFileInfo($location);

        return new Node(
            id: $id,
            path: $id,
            pathname: $location,
            url: $fileInfo->isFile() ? $this->mediaUrl.'/'.$id : null,
            size: $fileInfo->isFile() ? $fileInfo->getSize() : null,
            lastModified: (new \DateTime())->setTimestamp($fileInfo->getMTime()),
            type: $fileInfo->isFile() ? Node::TYPE_FILE : Node::TYPE_DIR,
        );
    }

    public function read(string $id): string
    {
        $location = $this->prefixPath($id);
        $contents = @file_get_contents($location);

        if (false === $contents) {
            throw new IOException(\sprintf("Unable to read file '%s'", $location), 0, null, $location);
        }

        return $contents;
    }

    public function write(string $id, string $contents, int $flags = \LOCK_EX): void
    {
        $location = $this->prefixPath($id);
        $this->checkDirectoryExists(\dirname($location));

        if (false === @file_put_contents($location, $contents, $flags)) {
            throw new IOException(\sprintf("Unable to write file '%s': ", $location).error_get_last()['message'], 0, null, $location);
        }
    }

    public function delete(string $id): void
    {
        $location = $this->prefixPath($id);

        if (!file_exists($location)) {
            return;
        }

        if (!@unlink($location)) {
            throw new IOException(\sprintf("Unable to delete file '%s'", $location), 0, null, $location);
        }
    }

    public function move(string $id, string $destination): void
    {
        $sourcePath = $this->prefixPath($id);
        $destinationPath = $this->prefixPath($destination);

        $this->checkDirectoryExists(\dirname($destinationPath));

        if (!@rename($sourcePath, $destinationPath)) {
            throw new IOException(\sprintf("Unable to move file from '%s' to '%s'", $sourcePath, $destinationPath), 0, null, $sourcePath);
        }
    }

    public function copy(string $id, string $destination): void
    {
        $sourcePath = $this->prefixPath($id);
        $destinationPath = $this->prefixPath($destination);

        $this->checkDirectoryExists(\dirname($destinationPath));

        if (!@copy($sourcePath, $destinationPath)) {
            throw new IOException(\sprintf("Unable to copy file '%s' to '%s'", $sourcePath, $destinationPath), 0, null, $sourcePath);
        }
    }

    public function listDirectory(string $id, bool $recursive = false): \Generator
    {
        $location = $this->prefixPath($id);
        $iterator = $recursive ? $this->listDirectoryRecursive($location) : new \DirectoryIterator($location);

        /** @var \SplFileInfo|\DirectoryIterator $fileInfo */
        foreach ($iterator as $fileInfo) {
            if ($fileInfo instanceof \DirectoryIterator && $fileInfo->isDot()) {
                continue;
            }

            if ($this->ignoreDotFiles && str_starts_with($fileInfo->getBasename(), '.')) {
                continue;
            }

            $path = $this->stripPrefix($fileInfo->getPathname());

            yield new Node(
                id: $path,
                path: $path,
                pathname: $fileInfo->getPathname(),
                size: $fileInfo->isFile() ? $fileInfo->getSize() : null,
                lastModified: (new \DateTime())->setTimestamp($fileInfo->getMTime()),
                type: $fileInfo->isFile() ? Node::TYPE_FILE : Node::TYPE_DIR,
            );
        }
    }

    private function listDirectoryRecursive(string $location): iterable
    {
        return yield from new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($location, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
    }

    public function createDirectory(string $id, int $permissions = 0o777): void
    {
        $location = $this->prefixPath($id);

        if (is_dir($location)) {
            return;
        }

        if (!@mkdir($location, $permissions, true)) {
            throw new IOException(\sprintf("Unable to create directory '%s'", $location), 0, null, $location);
        }
    }

    public function deleteDirectory(string $id): void
    {
        $location = $this->prefixPath($id);

        if (!is_dir($location)) {
            return;
        }

        $contents = $this->listDirectory($this->stripPrefix($location));

        /** @var Node $node */
        foreach ($contents as $node) {
            $node->isFile() ? @unlink($this->prefixPath($node->getPath())) : $this->deleteDirectory($node->getPath());
        }

        unset($contents);

        if (!@rmdir($location)) {
            throw new IOException(\sprintf("Unable to delete directory '%s'", $location), 0, null, $location);
        }
    }

    public function search(string $query): \Generator
    {
        $location = $this->prefixPath('');

        $iterator = $this->listDirectoryRecursive($location);

        /** @var \SplFileInfo|\DirectoryIterator $fileInfo */
        foreach ($iterator as $fileInfo) {
            if ($fileInfo instanceof \DirectoryIterator && $fileInfo->isDot()) {
                continue;
            }

            if ($this->ignoreDotFiles && str_starts_with($fileInfo->getBasename(), '.')) {
                continue;
            }

            $path = $this->stripPrefix($fileInfo->getPathname());

            if (!str_contains(basename($path), $query)) {
                continue;
            }

            yield new Node(
                id: $path,
                path: $path,
                pathname: $fileInfo->getPathname(),
                size: $fileInfo->isFile() ? $fileInfo->getSize() : null,
                lastModified: (new \DateTime())->setTimestamp($fileInfo->getMTime()),
                type: $fileInfo->isFile() ? Node::TYPE_FILE : Node::TYPE_DIR,
            );
        }
    }

    private function checkDirectoryExists(string $location): void
    {
        if (is_dir($location)) {
            return;
        }

        $location = $this->stripPrefix($location);
        $this->createDirectory($location);
    }

    private function prefixPath(string $path): string
    {
        return $this->rootLocation.ltrim($path, '\\/');
    }

    private function stripPrefix(string $path): string
    {
        return substr($path, \strlen($this->rootLocation));
    }
}
