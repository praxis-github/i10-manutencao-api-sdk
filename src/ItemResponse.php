<?php

namespace Praxis\I10ManutencaoApiSdk;

class ItemResponse
{
	private $data;

    /**
     * @param \stdClass $data
     */
	public function __construct(\stdClass $data)
	{
		$this->data = $data;
	}

	/**
	 * @return \stdClass
	 */
	public function getData()
	{
		return $this->data;
	}
}