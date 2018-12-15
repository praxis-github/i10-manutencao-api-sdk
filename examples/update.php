<?php

$sdk = new \Praxis\I10ManutencaoApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$resource = 'systems';

$id = 100;

$payload = [ 
	'descricao' => 'ABC', 
	'idEmpresa' => 1 
];

var_dump(
	$sdk->login($alias, $login, $password)
		->update('systems', $id, $payload)
		->toItemResponse()
);