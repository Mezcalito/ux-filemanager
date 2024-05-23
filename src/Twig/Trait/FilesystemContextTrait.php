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

namespace Mezcalito\FileManagerBundle\Twig\Trait;

use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

trait FilesystemContextTrait
{
    #[LiveProp(url: true)]
    public string $currentPath = '/';

    #[LiveListener('updateCurrentPath')]
    public function updateCurrentPath(#[LiveArg] string $path): void
    {
        $this->currentPath = $path;
    }
}
