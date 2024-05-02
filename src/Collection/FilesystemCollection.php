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

namespace Mezcalito\FileManagerBundle\Collection;

use Mezcalito\FileManagerBundle\Filesystem\Filesystem;

readonly class FilesystemCollection implements CollectionInterface
{
    /**
     * @param Filesystem[] $filesystems
     */
    public function __construct(
        private iterable $filesystems,
    ) {
    }

    public function get(string $name): Filesystem
    {
        $filesystem = $this->filesystems[$name] ?? null;
        if (null === $filesystem) {
            throw new \InvalidArgumentException(sprintf('Filesystem "%s" not found.', $name));
        }

        return $filesystem;
    }
}
