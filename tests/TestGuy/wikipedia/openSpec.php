<?php
$I = new WebTestGuy($scenario);
$I->wantTo('open wikipedia site');
$I->amOnPage('http://www.wikipedia.org');
$I->see('Google');
