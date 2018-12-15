<?php

$sdk = new \Praxis\I10ManutencaoApiSdk();

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