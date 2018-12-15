<?php

$sdk = new \Praxis\I10ManutencaoApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$id = 1;
$resource = 'systems';

var_dump(
	$sdk->login($alias, $login, $password)
		->show('systems', $id)
		->toItemResponse()
);