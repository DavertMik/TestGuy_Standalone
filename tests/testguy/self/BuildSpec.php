<?php
$I = new TestGuy($scenario);
$I->wantToTest('build command');
$I->execute('testguy.php','build');
$I->seeInOutput('generated sucessfully');