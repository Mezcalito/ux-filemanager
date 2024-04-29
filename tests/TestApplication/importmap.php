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

return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    '@hotwired/stimulus' => [
        'version' => '3.2.2',
    ],
    '@mezcalito/ux-filemanager' => [
        'path' => '../../assets/dist/controller.js',
    ],
    '@symfony/stimulus-bundle' => [
        'path' => '../../vendor/symfony/stimulus-bundle/assets/dist/loader.js',
    ],
    '@symfony/ux-live-component' => [
        'path' => '../../vendor/symfony/ux-live-component/assets/dist/live_controller.js',
    ],
];
