<?php 
// Set Path to universal loader
$symfonyPath = '/service/web/symfony/vendor/symfony';
$path = $symfonyPath . '/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';
require_once $path;

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Odl' => __DIR__ . '/../../../'
));

$loader->register();
