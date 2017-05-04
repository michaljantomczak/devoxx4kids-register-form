<?php

use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';
if(file_exists(dirname(__DIR__).DIRECTORY_SEPARATOR.'dev')){
    \Symfony\Component\Debug\Debug::enable();
    $kernel = new AppKernel('dev', true);
}
else{
    $kernel = new AppKernel('prod', false);
}

$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
