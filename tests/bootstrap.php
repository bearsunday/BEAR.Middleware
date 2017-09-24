<?php
/**
 * This file is part of the BEAR.Middleware package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('MyVendor\Weekday\\', __DIR__);
AnnotationRegistry::registerLoader([$loader, 'loadClass']);

require __DIR__ . '/Fake/header.php';
