<?php
require_once __DIR__.'/../../vendor/autoload.php';

$app = new \Silex\Application();
$app->register(new \Sorien\Provider\PimpleDumpProvider());
$app->register(new \Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__.'/views/twig',
    'twig.options' => [
        'debug' => true
    ]
]);

$app['debug'] = true;
$app['pimpledump.output_dir'] = __DIR__.'/../..';

$app['twig'] = $app->share($app->extend('twig', function ($twig, $app) {
    $version = SK\Digidoc\Digidoc::version();

    $twig->addGlobal('token', 'secure_value');
    $twig->addGlobal('version', $version);
    $twig->addGlobal('filename', "dds-hashcode-$version.tar.gz");
    $twig->addGlobal('updated', file_exists($filename) ? date("d.m.Y", filemtime("$filename")) : "N/A");

    return $twig;
}));

$app->get('/', function() use ($app) {
    return $app['twig']->render('index.twig');
});

$app->post('/new-container', function() use ($app) {
    return 'new document created';
});

$app->post('/existing-container', function() use ($app) {
   return 'existing container opened';
});

$app->run();
