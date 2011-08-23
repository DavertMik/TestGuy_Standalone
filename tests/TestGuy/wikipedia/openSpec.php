<?php
$I = new WebTestGuy($scenario);
$I->wantTo('open wikipedia site');
$I->amOnPage('http://wikipedia.org');
$I->see('The Free Encyclopedia');
