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

use Mezcalito\FileManagerBundle\Enum\ModalAction;
use Mezcalito\FileManagerBundle\Twig\Components\Modal;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

class ModalTest extends KernelTestCase
{
    use InteractsWithLiveComponents;

    #[DataProvider('provider')]
    public function testAction(string $action, ?string $oldValue, string $title, string $button): void
    {
        $component = $this->createLiveComponent(
            name: Modal::class,
            data: ['storage' => 'local', 'currentPath' => '/'],
        );
        $component->set('action', $action);
        if (null !== $oldValue) {
            $component->set('oldValue', $oldValue);
        }

        $content = $component->render()->toString();

        $this->assertStringContainsString($title, $content);
        $this->assertStringContainsString($button, $content);
    }

    public static function provider(): array
    {
        return [
            [ModalAction::CREATE->value, null, 'New folder', 'Create'],
            [ModalAction::DELETE_FOLDER->value, 'example', 'Delete folder &quot;example&quot;', 'Delete folder'],
            [ModalAction::DELETE_FILE->value, 'example.txt', 'Delete file &quot;example.txt&quot;', 'Delete file'],
            [ModalAction::UPLOAD->value, null, 'Upload files', 'Upload'],
            [ModalAction::RENAME->value, 'example', 'Rename item &quot;example&quot;', 'Rename'],
            [ModalAction::MOVE->value, 'example', 'Move item &quot;example&quot;', 'Move'],
        ];
    }
}
