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

use Mezcalito\FileManagerBundle\Provider\ProviderInterface;

readonly class Filesystem implements FilesystemInterface
{
    public function __construct(
        private ProviderInterface $provider,
    ) {
    }

    public function read(string $id): string
    {
        return $this->provider->read($id);
    }

    public function write(string $id, string $contents): void
    {
        $this->provider->write($id, $contents);
    }

    public function delete(string $id): void
    {
        $this->provider->delete($id);
    }

    public function move(string $id, string $source, string $destination): void
    {
        $this->provider->copy($id, $source, $destination);
    }

    public function copy(string $id, string $source, string $destination): void
    {
        $this->provider->copy($id, $source, $destination);
    }

    public function listDirectory(string $id, bool $recursive = false): \Generator
    {
        return $this->provider->listDirectory($id);
    }

    public function createDirectory(string $id, int $permissions = 0o777): void
    {
        $this->provider->createDirectory($id, $permissions);
    }

    public function deleteDirectory(string $id): void
    {
        $this->provider->deleteDirectory($id);
    }
}
