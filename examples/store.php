<?php

use Praxis\I10ManutencaoApiSdk\ApiSdk;

$sdk = new ApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$resource = 'systems';

$payload = [ 
	'descricao' => 'ABC', 
	'idEmpresa' => 1 
];

var_dump(
	$sdk->login($alias, $login, $password)
		->store('systems', $payload)
		->toItemResponse()
);