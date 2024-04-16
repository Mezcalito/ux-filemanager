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

readonly class Node implements NodeInterface
{
    public const string TYPE_FILE = 'file';

    public const string TYPE_DIR = 'dir';

    public function __construct(
        private string $id,
        private string $path,
        private string $type = self::TYPE_FILE,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function isFile(): bool
    {
        return self::TYPE_FILE === $this->type;
    }

    public function isDir(): bool
    {
        return self::TYPE_DIR === $this->type;
    }
}
