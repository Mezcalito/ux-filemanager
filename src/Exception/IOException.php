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

namespace Mezcalito\FileManagerBundle\Exception;

class IOException extends \RuntimeException implements ExceptionInterface
{
    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null, private readonly ?string $path = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function getPath(): ?string
    {
        return $this->path;
    }
}
