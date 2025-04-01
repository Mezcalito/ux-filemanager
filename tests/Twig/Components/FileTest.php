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

namespace Mezcalito\FileManagerBundle\Tests\Twig\Components;

use Mezcalito\FileManagerBundle\Twig\Components\File;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

class FileTest extends WebTestCase
{
    use InteractsWithLiveComponents;

    public function testDownloadAction(): void
    {
        $component = $this->createLiveComponent(
            name: File::class,
            data: ['storage' => 'local', 'currentPath' => '/', 'path' => 'image.jpg'],
        );

        $component->call('downloadFile', ['id' => 'image.jpg']);

        $this->assertInstanceOf(RedirectResponse::class, $component->response());
    }
}
