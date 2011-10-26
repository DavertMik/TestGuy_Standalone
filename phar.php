<?php

$p = new Phar('testguy.phar');
$p->compressFiles(Phar::GZ);
$p->startBuffering();
$p->buildFromDirectory('src');
$p->addFile('phar.stub.php');
$p->setStub($p->createDefaultStub('phar.stub.php'));
$p->stopBuffering();