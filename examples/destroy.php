<?php

use Praxis\I10ManutencaoApiSdk\ApiSdk;

$sdk = new ApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$id = 1;
$resource = 'systems';

var_dump(
	$sdk->login($alias, $login, $password)
		->destroy('systems', $id)
		->toItemResponse()
);