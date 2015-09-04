<?php
// File example: src/script.php

// update this to the path to the "vendor/"
// directory, relative to this file
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Finder\Finder;

$finder = new Finder();
$finder->files()->in(__DIR__.'/..');

foreach ($finder as $file) {
    // Dump the absolute path
    var_dump($file->getRealpath());

    // Dump the relative path to the file, omitting the filename
    var_dump($file->getRelativePath());

    // Dump the relative path to the file
    var_dump($file->getRelativePathname());
}