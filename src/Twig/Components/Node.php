<?php

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