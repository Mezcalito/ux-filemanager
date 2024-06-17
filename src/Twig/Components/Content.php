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

use Mezcalito\FileManagerBundle\Enum\Sort;
use Mezcalito\FileManagerBundle\Filesystem\Node;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemContextTrait;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('Mezcalito:Content', template: '@MezcalitoFileManager/components/content.html.twig')]
class Content
{
    use DefaultActionTrait;
    use FilesystemContextTrait;
    use FilesystemToolsTrait;

    #[LiveProp(writable: true)]
    public Sort $sort = Sort::NAME_ASC;

    #[ExposeInTemplate]
    public function getParentFolder(): ?string
    {
        if ('/' === $this->currentPath) {
            return null;
        }

        return \dirname('/'.$this->currentPath);
    }

    #[ExposeInTemplate]
    public function getContent(): array
    {
        $nodes = iterator_to_array($this->getFilesystem()->listDirectory($this->currentPath));
        usort($nodes, fn (Node $a, Node $b): int => match ($this->sort) {
            Sort::NAME_ASC => strcmp($a->getPath(), $b->getPath()),
            Sort::NAME_DESC => strcmp($b->getPath(), $a->getPath()),
            Sort::DATE_ASC => $a->getLastModified() <=> $b->getLastModified(),
            Sort::DATE_DESC => $b->getLastModified() <=> $a->getLastModified(),
        });

        return $nodes;
    }

    #[PreReRender]
    public function preReRender(): void
    {
        sleep(5);
    }
}
