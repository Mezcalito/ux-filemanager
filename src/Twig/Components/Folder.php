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

namespace Mezcalito\FileManagerBundle\Twig\Components;

use Mezcalito\FileManagerBundle\Filesystem\Node;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class Folder
{
    use DefaultActionTrait;
    use FilesystemToolsTrait;

    #[LiveProp]
    public string $path;

    #[ExposeInTemplate]
    public function getNode(): ?Node
    {
        return $this->getFilesystem()->info($this->path);
    }
}
