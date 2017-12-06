<?php
require_once __DIR__.'/../../vendor/autoload.php';

$app = new \Silex\Application();

$app->register(new \Sorien\Provider\PimpleDumpProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__.'/views/twig',
    'twig.options' => ['debug' => true, ],
]);
$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app['debug'] = true;

$app['twig'] = $app->share($app->extend('twig', function ($twig) {
    $version = SK\Digidoc\Digidoc::version();
    $filename = "dds-hashcode-$version.tar.gz";
    $updated = file_exists($filename) ? date('d.m.Y', filemtime($filename)) : 'N/A';

    $twig->addGlobal('token', 'secure_value');
    $twig->addGlobal('version', $version);
    $twig->addGlobal('filename', 'dds-hashcode-'.$version.'.tar.gz');
    $twig->addGlobal('updated', $updated);

    return $twig;
}));

$app->mount('/', new \SK\Digidoc\Example\Controller\StartController());
$app->mount('/container', new \SK\Digidoc\Example\Controller\ContainerController());

$app->run();
