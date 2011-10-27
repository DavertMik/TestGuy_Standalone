<?php

$I = new WebTestGuy($scenario);
$I->wantTo('sign in to LiveStreet');
$I->amOnPage('/');
$I->see('Войти');
$I->submitForm('#login_form form', array('login' => 'user', 'password' => 'useruser'));
$I->see('user', 'a.username');
$I->see('выход');
