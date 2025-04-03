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

use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveListener;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class Search
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use FilesystemToolsTrait;

    #[LiveProp(writable: true)]
    public ?string $query = null;

    #[LiveAction]
    #[ExposeInTemplate]
    public function search(): array
    {
        if (!$this->query) {
            return [];
        }

        $nodes = iterator_to_array($this->getFilesystem()->search($this->query));

        return \array_slice($nodes, 0, 10);
    }

    #[LiveListener('goToFolder')]
    public function goToFolder(#[LiveArg] string $path): void
    {
        $this->query = '';
        $this->emit('updateCurrentPath', ['path' => $path]);
    }
}
