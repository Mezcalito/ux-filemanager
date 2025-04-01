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

namespace Mezcalito\FileManagerBundle\Service;

class EncryptionService
{
    private readonly string $iv;

    public function __construct(
        private readonly string $kernelSecret,
    ) {
        $this->iv = substr(hash('sha256', $this->kernelSecret), 0, 16);
    }

    public function encrypt(string $data): string
    {
        return base64_encode(openssl_encrypt($data, 'aes-256-cbc', $this->kernelSecret, 0, $this->iv));
    }

    public function decrypt(string $encryptedData): false|string
    {
        return openssl_decrypt(base64_decode($encryptedData), 'aes-256-cbc', $this->kernelSecret, 0, $this->iv);
    }
}
