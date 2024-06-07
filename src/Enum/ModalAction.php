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

namespace Mezcalito\FileManagerBundle\Enum;

enum ModalAction: string
{
    case CREATE = 'create';
    case DELETE_FOLDER = 'delete-folder';
    case DELETE_FILE = 'delete-file';
    case UPLOAD = 'upload';
    case RENAME = 'rename';
    case MOVE = 'move';
}
