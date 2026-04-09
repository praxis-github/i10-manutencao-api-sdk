<?php

use Praxis\I10ManutencaoApiSdk\ApiSdk;

$sdk = new ApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$title = 'test 1';
$body = 'content test 1';
$usersIds = [1, 2, 3];

$sdk->login($alias, $login, $password)->dispatchNotification($title, $body, $usersIds);
