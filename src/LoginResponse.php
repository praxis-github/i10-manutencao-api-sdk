<?php

namespace Praxis\I10ManutencaoApiSdk;

class LoginResponse
{
	private $accessToken;
	private $tokenType;
	private $expiresIn;

    /**
     * @param string $accessToken
     * @param string $tokenType
     * @param int $expiresIn
     */
	public function __construct($accessToken, $tokenType, $expiresIn)
	{
		$this->accessToken = $accessToken;
		$this->tokenType = $tokenType;
		$this->expiresIn = $expiresIn;
	}

	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}

	/**
	 * @return string
	 */
	public function getTokenType()
	{
		return $this->tokenType;
	}

	/**
	 * @return int
	 */
	public function getExpiresIn()
	{
		return $this->expiresIn;
	}
}