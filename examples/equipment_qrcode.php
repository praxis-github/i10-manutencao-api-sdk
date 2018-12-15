<?php

use Praxis\I10ManutencaoApiSdk\ApiSdk;

$sdk = new ApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$equipmentId = 1;
$endpoint = sprintf('equipments/%s/qr-code', $equipmentId);

$sdk->login($alias, $login, $password);

$qrCodeResponse = $sdk->show($endpoint, null, [
	'Accept' => 'text/html'
]);

echo $qrCodeResponse->toRaw();