<?php

use Praxis\I10ManutencaoApiSdk\ApiSdk;

$sdk = new ApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$id = 1;
$endpoint = 'equipments/1000/qr-code';

$sdk->login($alias, $login, $password);

$qrCodeResponse = $sdk->show($endpoint, null, [
	'Accept' => 'text/html'
]);

var_dump($qrCodeResponse->toRaw());