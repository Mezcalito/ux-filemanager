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

namespace Mezcalito\FileManagerBundle\Tests\Provider\Factory;

use Mezcalito\FileManagerBundle\Provider\Factory\LocalFilesystemProviderFactory;
use Mezcalito\FileManagerBundle\Provider\LocalFilesystemProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalFilesystemProviderFactoryTest extends TestCase
{
    public function testCreateDefinition(): void
    {
        $factory = new LocalFilesystemProviderFactory();

        $resolver = new OptionsResolver();
        $factory->configureResolver($resolver);

        $providerDefinition = $factory->createDefinition($resolver->resolve(['path' => '/tmp']));

        $this->assertEquals(LocalFilesystemProvider::class, $providerDefinition->getClass());
        $this->assertEquals(['/tmp', null], $providerDefinition->getArguments());
    }
}
