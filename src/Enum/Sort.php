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

enum Sort: string
{
    case NAME_ASC = 'name-asc';
    case NAME_DESC = 'name-desc';
    case DATE_ASC = 'date-asc';
    case DATE_DESC = 'date-desc';
}
