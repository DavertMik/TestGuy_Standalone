<?php
$I = new TestGuy($scenario);
$I->wantToTest('running a test suite');
$I->execute('php testguy.php run self build');
$I->seeInOutput('test build command');
$I->seeInOutput('TestGuy '.TestGuy_Standalone_Manager::VERSION);
$I->seeInOutput('OK (1 test');

$I->amGoingTo('check generation of html output');
$I->execute('php testguy.php run self build --html');
$I->seeFileFound('result.html','tests/log');
$I->seeInFile('TestGuy Results');
