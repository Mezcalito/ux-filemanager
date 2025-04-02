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
use Mezcalito\FileManagerBundle\Service\EncryptionService;
use Mezcalito\FileManagerBundle\Twig\Trait\FilesystemToolsTrait;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentToolsTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent]
class File
{
    use ComponentToolsTrait;
    use DefaultActionTrait;
    use FilesystemToolsTrait;

    #[LiveProp]
    public string $path;

    public function __construct(
        private readonly EncryptionService $encryptionService,
        private readonly RouterInterface $router,
    ) {
    }

    #[ExposeInTemplate]
    public function getNode(): ?Node
    {
        return $this->getFilesystem()->info($this->path);
    }

    #[ExposeInTemplate]
    public function getBase64Content(): ?string
    {
        $fileInfo = $this->getNode();
        if (!\in_array($fileInfo->getExtension(), ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp', 'bmp'])) {
            return null;
        }

        return base64_encode($this->getFilesystem()->read($this->path));
    }

    #[LiveAction]
    public function selectMedia(#[LiveArg] string $url): void
    {
        $this->dispatchBrowserEvent('filemanager:selectMedia', [
            'url' => $url,
        ]);
    }

    #[LiveAction]
    public function downloadFile(#[LiveArg] string $id): RedirectResponse
    {
        $infos = $this->getFilesystem()->info($id);

        $redirectUrl = $this->router->generate('mezcalito_filemanager_download', [
            'path' => $this->encryptionService->encrypt($infos->getPathname()),
            'name' => $infos->getFilename(),
        ]);

        return new RedirectResponse($redirectUrl);
    }
}
