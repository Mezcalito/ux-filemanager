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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PreReRender;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
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

    public function __construct(
        private readonly ?TranslatorInterface $translator = null,
    ) {
    }

    #[PreReRender]
    public function preRender(): void
    {
        if (ModalAction::RENAME == ModalAction::tryFrom($this->action ?? '')) {
            $this->value = basename((string) $this->oldValue);
        }
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
            ModalAction::CREATE => $this->trans('New folder'),
            ModalAction::DELETE_FOLDER => $this->trans('Delete folder "%s"'),
            ModalAction::DELETE_FILE => $this->trans('Delete file "%s"'),
            ModalAction::UPLOAD => $this->trans('Upload files'),
            ModalAction::RENAME => $this->trans('Rename item "%s"'),
            ModalAction::MOVE => $this->trans('Move item "%s"'),
            default => '',
        };

        return \sprintf($title, $this->oldValue);
    }

    #[ExposeInTemplate]
    public function getSubtitle(): ?string
    {
        $subtitle = match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::DELETE_FOLDER, ModalAction::DELETE_FILE => $this->trans('"%s" will be permanently deleted.'),
            ModalAction::MOVE => $this->trans('Select a folder below to move your item to.'),
            default => '',
        };

        return \sprintf($subtitle, $this->oldValue);
    }

    #[ExposeInTemplate]
    public function getContent(): ?string
    {
        return match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::CREATE, ModalAction::RENAME => 'input',
            ModalAction::UPLOAD => 'upload',
            ModalAction::MOVE => 'files',
            default => null,
        };
    }

    #[ExposeInTemplate]
    public function getActionLabel(): ?string
    {
        return $this->trans(ucfirst(str_replace('-', ' ', $this->action ?? '')));
    }

    #[LiveAction]
    public function save(Request $request): void
    {
        match (ModalAction::tryFrom($this->action ?? '')) {
            ModalAction::CREATE => $this->getFilesystem()->createDirectory($this->prefixPath($this->value)),
            ModalAction::DELETE_FOLDER => $this->getFilesystem()->deleteDirectory($this->oldValue),
            ModalAction::DELETE_FILE => $this->getFilesystem()->delete($this->oldValue),
            ModalAction::UPLOAD => $this->uploadFiles($request->files->all('uploads')),
            ModalAction::RENAME => $this->getFilesystem()->move($this->oldValue, $this->prefixPath($this->value)),
            ModalAction::MOVE => $this->getFilesystem()->move($this->oldValue, $this->value.'/'.basename((string) $this->oldValue)),
            default => null,
        };

        $this->dispatchBrowserEvent('filemanager:refresh');
        $this->dispatchBrowserEvent('modal:close');
        $this->value = null;
        $this->oldValue = null;
    }

    /**
     * @param UploadedFile[] $files
     */
    private function uploadFiles(array $files): void
    {
        foreach ($files as $file) {
            $this->getFilesystem()->write($this->prefixPath($file->getClientOriginalName()), $file->getContent());
        }
    }

    private function prefixPath(string $value): string
    {
        if ('/' === $this->currentPath) {
            return $this->currentPath.$value;
        }

        return \sprintf('/%s/%s', $this->currentPath, $value);
    }

    private function trans(string $message): string
    {
        return null !== $this->translator ? $this->translator->trans($message, [], 'FileManagerBundle') : $message;
    }
}
