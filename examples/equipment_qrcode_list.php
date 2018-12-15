<?php

use Praxis\I10ManutencaoApiSdk\ApiSdk;
use Praxis\I10ManutencaoApiSdk\RequestData;

$sdk = new ApiSdk();

$alias = 'xpto';
$login = 'fulano';
$password = '12345';

$page = 1;
$filters = [];
$limit = 100;

$requestData = new RequestData($page, $filters, $limit);

$endpoint = 'equipments/qr-code';

$sdk->login($alias, $login, $password);

$qrCodeResponse = $sdk->index($endpoint, $requestData, [
	'Accept' => 'text/html'
]);

echo $qrCodeResponse->toRaw();