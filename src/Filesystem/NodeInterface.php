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

interface NodeInterface
{
    public function getId(): string;

    public function getPath(): string;

    public function getFilename(): string;

    public function getExtension(): ?string;

    public function getPathname(): string;

    public function getUrl(): ?string;

    public function getSize(): ?int;

    public function isFile(): bool;

    public function isDir(): bool;
}
