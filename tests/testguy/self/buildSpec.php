<?php
$I = new TestGuy($scenario);
$I->wantToTest('build command');
$I->execute('php testguy.php build --silent');
$I->seeInOutput('generated sucessfully');
$I->seeFileFound('TestGuy.php','tests');
$I->seeInFile('seeInFile($text)');
