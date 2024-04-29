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

namespace Mezcalito\FileManagerBundle\Tests\TestApplication;

use Mezcalito\FileManagerBundle\MezcalitoFileManagerBundle;
use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\LiveComponent\LiveComponentBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;
use Twig\Environment;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function index(): Response
    {
        return new Response('index');
    }

    public function renderTemplate(string $template, ?Environment $twig = null): Response
    {
        $twig ??= $this->container->get('twig');

        return new Response($twig->render($template.'.html.twig'));
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new TwigComponentBundle();
        yield new LiveComponentBundle();
        yield new StimulusBundle();
        yield new MezcalitoFileManagerBundle();
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('@LiveComponentBundle/config/routes.php')
            ->prefix('/_components');

        $routes->add('homepage', '/')
            ->controller('kernel::index');

        $routes->add('template', '/render-template/{template}')
            ->controller('kernel::renderTemplate');
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $frameworkConfig = [
            'secret' => 'S3CRET',
            'test' => true,
            'router' => ['utf8' => true],
            'secrets' => false,
            'session' => ['storage_factory_id' => 'session.storage.factory.mock_file'],
            'http_method_override' => false,
            'property_info' => ['enabled' => true],
            'php_errors' => ['log' => true],
            'asset_mapper' => ['paths' => ['assets/', '../../assets/']],
        ];

        if (self::VERSION_ID >= 60400) {
            $frameworkConfig['handle_all_throwables'] = true;
            $frameworkConfig['session'] = [
                'storage_factory_id' => 'session.storage.factory.mock_file',
                'cookie_secure' => 'auto',
                'cookie_samesite' => 'lax',
                'handler_id' => null,
            ];
            $frameworkConfig['annotations']['enabled'] = false;
        }

        $container->extension('framework', $frameworkConfig);

        $container->extension('twig', [
            'default_path' => '%kernel.project_dir%/templates',
        ]);

        $container->extension('mezcalito_file_manager', [
            'storages' => [
                'local' => [
                    'provider' => 'local',
                    'options' => [
                        'path' => '%kernel.project_dir%/../fixtures/storages/local',
                    ],
                ],
            ],
        ]);

        $container->services()
            ->defaults()
            ->autowire()
            ->autoconfigure()
            // disable logging errors to the console
            ->set('logger', NullLogger::class);
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir().'/mezcalito_ux_file_manager/tests/var/'.$this->environment.'/cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir().'/mezcalito_ux_file_manager/tests/var/'.$this->environment.'/log';
    }
}
