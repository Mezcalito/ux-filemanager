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

namespace Mezcalito\FileManagerBundle\Provider\Factory;

use Mezcalito\FileManagerBundle\Provider\ProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ProviderFactoryInterface
{
    public function support(string $name): bool;

    public function configureResolver(OptionsResolver $resolver): void;

    public function create(array $options): ?ProviderInterface;
}
