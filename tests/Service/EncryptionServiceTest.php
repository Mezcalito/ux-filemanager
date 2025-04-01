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

namespace Mezcalito\FileManagerBundle\Tests\Service;

use Mezcalito\FileManagerBundle\Service\EncryptionService;
use PHPUnit\Framework\TestCase;

class EncryptionServiceTest extends TestCase
{
    private EncryptionService $encryptionService;

    protected function setUp(): void
    {
        $kernelSecret = 'secret4Test';
        $this->encryptionService = new EncryptionService($kernelSecret);
    }

    public function testEncryptAndDecrypt(): void
    {
        $originalData = '/public/file/test.png';

        $encryptedData = $this->encryptionService->encrypt($originalData);

        $this->assertNotNull($encryptedData);
        $this->assertIsString($encryptedData);

        $decryptedData = $this->encryptionService->decrypt($encryptedData);

        $this->assertSame($originalData, $decryptedData);
    }

    public function testDecryptWithInvalidData(): void
    {
        $invalidEncryptedData = 'invalidEncryptedData';

        $decryptedData = $this->encryptionService->decrypt($invalidEncryptedData);

        $this->assertFalse($decryptedData);
    }
}
