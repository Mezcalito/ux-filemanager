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

namespace Mezcalito\FileManagerBundle\Tests;

function create_directory(string $dir): void
{
    if (is_dir($dir)) {
        return;
    }

    mkdir($dir, 0o777, true);
}

function delete_directory(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }

    foreach (scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) {
            continue;
        }

        if (is_dir($dir.'/'.$file)) {
            delete_directory($dir.'/'.$file);
        } else {
            unlink($dir.'/'.$file);
        }
    }

    rmdir($dir);
}
