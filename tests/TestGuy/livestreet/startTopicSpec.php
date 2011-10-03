<?php

$I = new WebTestGuy($scenario);
$I->wantTo('start new topic');
$I->amOnPage('/');
$I->submitForm('#login_form form', array('login' => 'user', 'password' => 'useruser'));
$I->see('написать');
$I->click('написать');
$I->submitForm('#content-inner form', array('topic_title' => 'Функциональное тестирование', 'topic_text' => 'Мы используем TestGuy для тестирования', 'topic_tags' => 'tdd'));
$I->see('Функциональное тестирование');
$I->see('Комментарии');
