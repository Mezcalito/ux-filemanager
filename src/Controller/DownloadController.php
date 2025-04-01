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

namespace Mezcalito\FileManagerBundle\Controller;

use Mezcalito\FileManagerBundle\Service\EncryptionService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

readonly class DownloadController
{
    public function __construct(private EncryptionService $encryptionService)
    {
    }

    public function __invoke(string $path, string $name): BinaryFileResponse
    {
        $url = $this->encryptionService->decrypt($path);

        if (!$url) {
            throw new NotFoundHttpException('File not found');
        }

        $response = new BinaryFileResponse($url);

        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );

        return $response;
    }
}
