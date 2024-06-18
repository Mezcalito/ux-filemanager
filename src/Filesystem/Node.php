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
        private string $pathname,
        private ?string $url = null,
        private ?int $size = null,
        private ?\DateTime $lastModified = null,
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

    public function getFilename(): string
    {
        return basename($this->path);
    }

    public function getExtension(): ?string
    {
        if ($this->isDir()) {
            return null;
        }

        return pathinfo($this->path, \PATHINFO_EXTENSION);
    }

    public function getPathname(): string
    {
        return $this->pathname;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getLastModified(): ?\DateTime
    {
        return $this->lastModified;
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
