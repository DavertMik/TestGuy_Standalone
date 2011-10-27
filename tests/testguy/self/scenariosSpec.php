<?php
$I = new TestGuy($scenario);
$I->wantToTest('generate-scenarios command');
$I->execute('php testguy.php generate-scenarios');
$I->seeInOutput('scenarios generated');
$I->seeFileFound('scenarios.txt','tests/log/scenarios');

$I->expectTo('see this files translated into human language');
$I->seeInFile('I want to test generate-scenarios command');
$I->seeInFile('I execute "php testguy.php generate-scenarios"');
