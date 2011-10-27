#!/usr/bin/env php
<?php

if (file_exists('testguy.phar')) unlink('testguy.phar');

$p = new Phar('testguy.phar');
$p->startBuffering();
$p->buildFromDirectory('..','~\.php$~');
$p->setStub(file_get_contents('stub.php'));
$p->stopBuffering();
// $p->compressFiles(Phar::GZ);