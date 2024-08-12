<?php

declare(strict_types=1);

$header = <<<'HEADER'
This file is part of the Mezcalito UX FileManager project.

(c) Mezcalito <dev@mezcalito.fr>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
HEADER;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
        __DIR__ . '/translations',
    ])
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP84Migration' => true,
        '@PHPUnit100Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => $header,
            'location' => 'after_open',
        ],
    ])
    ->setFinder($finder);