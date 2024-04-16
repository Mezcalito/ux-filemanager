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

interface FilesystemInterface
{
    public function read(string $id): string;

    public function write(string $id, string $contents): void;

    public function delete(string $id): void;

    public function move(string $id, string $source, string $destination): void;

    public function copy(string $id, string $source, string $destination): void;

    public function listDirectory(string $id, bool $recursive = false): \Generator;

    public function createDirectory(string $id, int $permissions = 0o777): void;

    public function deleteDirectory(string $id): void;
}
