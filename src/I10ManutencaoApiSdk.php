<?php

namespace Praxis\I10ManutencaoApiSdk;

require_once "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\GuzzleException;

class I10ManutencaoApiSdk
{
	private $baseUrl = 'http://gcpmail3.task.com.br/v1/';	
	private $client = null;
	private $accessToken = null;
	private $rawResponse = null;

	/**
	 * @param string $alias
	 * @param string $login
	 * @param string $password
	 * @return I10ManutencaoApiSdk
	 */
	public function login($alias, $login, $password)
	{
		$requestClient = $this->createClient($this->baseUrl);

		try {
			$response = $requestClient->post('auth/login', [
				'body' => json_encode([					
					'alias' => $alias,
					'password' => $password,
					'login' => $login
				])
			]);
		} catch (ClientException $e) {
			$statusCode = $e->getResponse()->getStatusCode();
			
			if ($statusCode) {
				throw new UserNotFoundException('User not found');
			}
		}				
		$content = json_decode($response->getBody()->getContents());

		$this->accessToken = $content->access_token;
		
		$this->client = $this->createClient(
			$this->baseUrl,
			$content->access_token
		);

		return $this;
	}

	/**
	 * @param string $baseUrl
	 * @param string $accessToken
	 * @return \GuzzleHttp\Client\ClientInterface
	 */
	private function createClient($baseUrl = null, $accessToken = null)
	{
		$headers = [
			'Content-Type' => 'application/json'
		];

		if (! is_null($accessToken)) {
			$headers['Authorization'] = sprintf('Bearer %s', $accessToken);
		}

		return new Client([
			'base_url' => $baseUrl,
			'defaults' => [
				'headers' => $headers
			]
		]);
	}

	/**
	 * @param string $resource
	 * @param RequestData $requestData
	 * @param array $headers
	 * @return I10ManutencaoApiSdk
	 */
	public function index($resource, RequestData $requestData = null, array $headers = [])
	{
		$endpoint = $resource;

		if ($requestData instanceof RequestData) {
			$endpoint = sprintf('%s?%s', $endpoint, (string)$requestData);
		}
		try {
			$response = $this->client->get(
				$endpoint,
				[
					'headers' => array_merge(
						[
							'Content-Type' => 'application/json'
						],
						$headers
					)										
				]				
			);	
		} catch (\Exception $e) {
		 	
		}
		$this->rawResponse = (string)$response->getBody();

		return $this;
	}

	/**
	 * @param string $resource
	 * @param integer $id
	 * @param array $headers
	 * @return I10ManutencaoApiSdk
	 */
	public function show($resource, $id = -1, array $headers = [])
	{
		$endpoint = $resource;

		if ($id !== -1) {
			$endpoint = sprintf('%s/%s', $endpoint, $id);
		}
		try {
			$response = $this->client->get(
				$endpoint,
				[
					'headers' => array_merge(
						[
							'Content-Type' => 'application/json'
						],
						$headers
					)
				]				
			);	
		} catch (\Exception $e) {
		 	
		}
		$this->rawResponse = (string)$response->getBody();

		return $this;
	}

	/**
	 * @param string $endpoint
	 * @param array $payload
	 * @param array $headers
	 * @return I10ManutencaoApiSdk
	 */
	public function store($endpoint, array $payload = [], array $headers = [])
	{
		try {
			$response = $this->client->post(
				$endpoint,
				[
					'body' => json_encode($payload),
					'headers' => array_merge(
						[
							'Content-Type' => 'application/json'
						],
						$headers
					)
				]				
			);	
		} catch (\Exception $e) {
			
		}
		$this->rawResponse = (string)$response->getBody();

		return $this;
	}

	/**
	 * @param string $resource
	 * @param integer $id
	 * @param array $payload
	 * @param array $headers
	 * @return I10ManutencaoApiSdk
	 */
	public function update($resource, $id = -1, array $payload = [], array $headers = [])
	{
		$endpoint = $resource;

		if ($id !== -1) {
			$endpoint = sprintf('%s/%s', $endpoint, $id);
		}
		try {
			$response = $this->client->put(
				$endpoint,
				[
					'body' => json_encode($payload),
					'headers' => array_merge(
						[
							'Content-Type' => 'application/json'
						],
						$headers
					)
				]				
			);	
		} catch (\Exception $e) {
		 	
		}
		$this->rawResponse = (string)$response->getBody();

		return $this;
	}

	/**
	 * @param string $resource
	 * @param integer $id
	 * @param array $headers
	 * @return I10ManutencaoApiSdk
	 */
	public function destroy($resource, $id = -1, array $headers = [])
	{
		$endpoint = $resource;

		if ($id !== -1) {
			$endpoint = sprintf('%s/%s', $endpoint, $id);
		}
		try {
			$response = $this->client->delete(
				$endpoint,
				[
					'headers' => array_merge(
						[
							'Content-Type' => 'application/json'
						],
						$headers
					)
				]				
			);	
		} catch (\Exception $e) {
		 	
		}
		$this->rawResponse = (string)$response->getBody();

		return $this;		
	}

	/**
	 * @return string
	 */
	public function toRaw()
	{
		if (is_null($this->rawResponse)) {
			throw new \Exception('Response is not exists');
		}

		return $this->rawResponse;
	}

	/**
	 * @return \stdClass
	 */
	public function toObject()
	{
		if (is_null($this->rawResponse)) {
			throw new \Exception('Response is not exists');
		}
		$response = json_decode($this->rawResponse);

		return $response;
	}

	/**
	 * @return PaginatedResponse
	 */
	public function toPaginatedResponse()
	{
		if (is_null($this->rawResponse)) {
			throw new \Exception('Response is not exists');
		}
		$response = json_decode($this->rawResponse);

		try {
			if (! isset($response->meta)) {
				throw new \Exception('This response not contains "metadata" information');	
			}

			if (! isset($response->data)) {
				throw new \Exception('This response not contains "data" information');	
			}
			$paginatedResponse = new PaginatedResponse($response->data, $response->meta);
		} catch (\Exception $e) {
			throw new \Exception(sprintf('Error on response conversion : %s', $e->getMessage()));
		}
	
		return $paginatedResponse;
	}

	/**
	 * @return ItemResponse
	 */
	public function toItemResponse()
	{
		if (is_null($this->rawResponse)) {
			throw new \Exception('Response is not exists');
		}
		$response = json_decode($this->rawResponse);

		try {
			if (! isset($response->data)) {
				throw new \Exception('This response not contains "data" information');	
			}
			$itemResponse = new ItemResponse($response->data);
		} catch (\Exception $e) {
			throw new \Exception(sprintf('Error on response conversion : %s', $e->getMessage()));
		}

		return $itemResponse;
	}
}