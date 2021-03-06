<?php

namespace Praxis\I10ManutencaoApiSdk;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\GuzzleException;

class ApiSdk
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
			
			if ($statusCode === 404) {
				throw new NotFoundException('User not found');
            }
            throw new Exception(sprintf('Error : %s', $e->getMessage()));            
		}				
		$content = json_decode($response->getBody()->getContents());

        $this->accessToken = $content->access_token;
		
		$this->client = $this->createClient(
			$this->baseUrl,
			$this->accessToken
		);

		return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
    
    /**
     * @param string $token
     * @return I10ManutencaoApiSdk
     */
    public function refreshToken($token)
    {
        $requestClient = $this->createClient($this->baseUrl);

        try {
			$response = $requestClient->post('auth/refresh', [
				'body' => json_encode([					
                    'token' => $token
                ])
			]);
		} catch (ClientException $e) {
            throw new \Exception($e->getMessage());            
		}
        $content = json_decode($response->getBody()->getContents());
        
		$this->accessToken = $content->access_token;
		
		$this->client = $this->createClient(
			$this->baseUrl,
			$this->accessToken
		);

		return $this;
    }

	/**
	 * @param string $baseUrl
	 * @param string $accessToken
	 * @return ClientInterface
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
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if ($statusCode === 401) {                
                $this->refreshToken($this->accessToken);
                return $this->index($resource, $requestData, $headers);                
            }        
            throw new Exception(sprintf('Error : %s', $e->getMessage()));
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
		} catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();

            if ($statusCode === 404) {
                throw new NotFoundException('Not found!');
            }

            if ($statusCode === 401) {                
                $this->refreshToken($this->accessToken);
                return $this->show($resource, $id, $headers);
            }
            throw new Exception(sprintf('Error : %s', $e->getMessage()));
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
	public function store($endpoint, array $payload = [], array $headers = [], $isApplicationJson = true)
	{
		try {
			$response = $this->client->post(
				$endpoint,
				[
					'body' => $isApplicationJson ? json_encode($payload) : $payload,
					'headers' => array_merge(
						[
							'Content-Type' => 'application/json'
						],
						$headers
					)
				]
			);	
		} catch (ClientException $e) {
			$statusCode = $e->getResponse()->getStatusCode();            

            if ($statusCode === 401) {                
                $this->refreshToken($this->accessToken);
                return $this->store($endpoint, $payload, $headers);
            }
            throw new Exception(sprintf('Error : %s', $e->getMessage()));
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
		} catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();            

            if ($statusCode === 401) {                
                $this->refreshToken($this->accessToken);
                return $this->update($resource, $id, $payload, $headers);
            }
            throw new Exception(sprintf('Error : %s', $e->getMessage()));
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
		} catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();            

            if ($statusCode === 401) {                
                $this->refreshToken($this->accessToken);
                return $this->destroy($resource, $id, $headers);
            }
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
