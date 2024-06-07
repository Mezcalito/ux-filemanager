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

use Mezcalito\FileManagerBundle\Enum\ModalAction;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemContextTrait;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent('Mezcalito:Modal', template: '@MezcalitoFileManager/components/modal.html.twig')]
class Modal
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use FilesystemContextTrait;
    use FilesystemToolsTrait;

    #[LiveProp(writable: true)]
    public ?string $action = null;

    #[LiveProp(writable: true)]
    public ?string $oldValue = null;

    #[LiveProp(writable: true)]
    public ?string $value = null;

    #[PreReRender]
    public function preRender(): void
    {
        $this->value = $this->oldValue;
    }

    #[ExposeInTemplate]
    public function getSize(): string
    {
        return match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::MOVE => 'lg',
            default => 'md',
        };
    }

    #[ExposeInTemplate]
    public function getTitle(): ?string
    {
        $title = match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::CREATE => 'New folder',
            ModalAction::DELETE_FOLDER => 'Delete folder "%s"',
            ModalAction::DELETE_FILE => 'Delete file "%s"',
            ModalAction::RENAME => 'Rename your folder',
            ModalAction::MOVE => 'Move item "%s"',
            default => '',
        };

        return sprintf($title, $this->oldValue);
    }

    #[ExposeInTemplate]
    public function getSubtitle(): ?string
    {
        $subtitle = match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::DELETE_FOLDER, ModalAction::DELETE_FILE => '"%s" will be permanently deleted.',
            ModalAction::MOVE => 'Select a folder below to move your item to.',
            default => '',
        };

        return sprintf($subtitle, $this->oldValue);
    }

    #[ExposeInTemplate]
    public function getContent(): ?string
    {
        return match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::CREATE, ModalAction::RENAME => 'input',
            ModalAction::MOVE => 'files',
            default => null,
        };
    }

    #[ExposeInTemplate]
    public function getActionLabel(): ?string
    {
        return str_replace('-', ' ', $this->action ?? '');
    }

    #[LiveAction]
    public function save(): void
    {
        match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::CREATE => $this->getFilesystem()->createDirectory($this->getPath($this->value)),
            ModalAction::DELETE_FOLDER => $this->getFilesystem()->deleteDirectory($this->getPath($this->value)),
            ModalAction::DELETE_FILE => $this->getFilesystem()->delete($this->getPath($this->value)),
            ModalAction::UPLOAD => null,
            ModalAction::RENAME => $this->getFilesystem()->move($this->getPath($this->oldValue), $this->getPath($this->value)),
            ModalAction::MOVE => null,
            default => null,
        };

        $this->dispatchBrowserEvent('filemanager:refresh');
        $this->dispatchBrowserEvent('modal:close');
    }

    private function getPath(string $value): string
    {
        if ('/' === $this->currentPath) {
            return $this->currentPath.$value;
        }

        return sprintf('/%s/%s', $this->currentPath, $value);
    }
}
