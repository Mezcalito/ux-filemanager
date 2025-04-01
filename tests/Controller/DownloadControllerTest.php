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

namespace Mezcalito\FileManagerBundle\Tests\Controller;

use Mezcalito\FileManagerBundle\Controller\DownloadController;
use Mezcalito\FileManagerBundle\Service\EncryptionService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadControllerTest extends TestCase
{
    private DownloadController $controller;

    private MockObject|EncryptionService $encryptionServiceMock;

    protected function setUp(): void
    {
        $this->encryptionServiceMock = $this->createMock(EncryptionService::class);
        $this->controller = new DownloadController($this->encryptionServiceMock);
    }

    public function testInvokeWithValidPath(): void
    {
        $decryptedPath = 'tests/Controller/DownloadControllerTest.php';
        $this->encryptionServiceMock
            ->expects($this->once())
            ->method('decrypt')
            ->with('encryptedPath')
            ->willReturn($decryptedPath);

        $response = $this->controller->__invoke('encryptedPath', 'DownloadControllerTest.php');

        $this->assertInstanceOf(BinaryFileResponse::class, $response);

        $this->assertStringContainsString('DownloadControllerTest.php', $response->headers->get('Content-Disposition'));
    }

    public function testInvokeWithInvalidPath(): void
    {
        $this->encryptionServiceMock
            ->expects($this->once())
            ->method('decrypt')
            ->with('encryptedPath')
            ->willReturn(false);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->__invoke('encryptedPath', 'file.txt');
    }
}
