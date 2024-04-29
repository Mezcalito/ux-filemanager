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

readonly class Storage implements StorageInterface
{
    public function __construct(
        private ProviderInterface $provider,
    ) {
    }

    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }
}