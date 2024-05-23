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

use Mezcalito\FileManagerBundle\Filesystem\Node as FsNode;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('Mezcalito:Node', template: '@MezcalitoFileManager/components/node.html.twig')]
class Node
{
    use DefaultActionTrait;
    use FilesystemToolsTrait;

    #[LiveProp]
    public string $id;

    public function getNode(): ?FsNode
    {
        return $this->filesystem->info($this->id);
    }

    public function getBase64Content(): string
    {
        return base64_encode($this->filesystem->read($this->id));
    }
}
