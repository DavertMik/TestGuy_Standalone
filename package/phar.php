<?php

$p = new Phar('testguy.phar');
$p->startBuffering();
$p->buildFromDirectory('..','/\.php$/');
$p->setStub(file_get_contents('stub.php'));
$p->stopBuffering();
$p->compressFiles(Phar::GZ);