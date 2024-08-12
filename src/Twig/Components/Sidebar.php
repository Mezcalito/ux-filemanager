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
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemContextTrait;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class Sidebar
{
    use DefaultActionTrait;
    use FilesystemContextTrait;
    use FilesystemToolsTrait;

    #[ExposeInTemplate]
    public function getNodes(): array
    {
        $nodes = iterator_to_array($this->getFilesystem()->listDirectory('/'));
        $nodes = array_filter($nodes, static fn (Node $node) => $node->isDir());
        usort($nodes, static fn (Node $a, Node $b): int => strcmp($a->getPath(), $b->getPath()));

        return $nodes;
    }
}
