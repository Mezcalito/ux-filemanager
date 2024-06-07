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

namespace Mezcalito\FileManagerBundle\Filesystem;

readonly class Filesystem implements FilesystemInterface
{
    public function __construct(
        private StorageInterface $storage,
    ) {
    }

    public function info(string $id): Node
    {
        return $this->storage->getProvider()->info($id);
    }

    public function read(string $id): string
    {
        return $this->storage->getProvider()->read($id);
    }

    public function write(string $id, string $contents): void
    {
        $this->storage->getProvider()->write($id, $contents);
    }

    public function delete(string $id): void
    {
        $this->storage->getProvider()->delete($id);
    }

    public function move(string $id, string $destination): void
    {
        $this->storage->getProvider()->move($id, $destination);
    }

    public function copy(string $id, string $destination): void
    {
        $this->storage->getProvider()->copy($id, $destination);
    }

    public function listDirectory(string $id, bool $recursive = false): \Generator
    {
        return $this->storage->getProvider()->listDirectory($id);
    }

    public function createDirectory(string $id, int $permissions = 0o777): void
    {
        $this->storage->getProvider()->createDirectory($id, $permissions);
    }

    public function deleteDirectory(string $id): void
    {
        $this->storage->getProvider()->deleteDirectory($id);
    }
}
