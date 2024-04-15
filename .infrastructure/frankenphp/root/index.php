<?php

use Mezcalito\FileManagerBundle\Tests\Fixtures\Kernel;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/../../vendor/autoload.php';

$kernel = new Kernel('dev', true);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);