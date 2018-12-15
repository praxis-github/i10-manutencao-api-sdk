<?php

namespace Praxis\I10ManutencaoApiSdk;
class PaginatedResponse
{
	private $data;
	private $metadata;

	/**
	 * @param array $data
	 * @param \stdClass $metadata
	 */
	public function __construct(array $data, \stdClass $metadata)
	{
		$this->data = $data;
		$this->metadata = $metadata;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @return \stdClass
	 */
	public function getMetadata()
	{
		return $this->metadata;
	}

	/**
	 * @return integer
	 */
	public function getTotal()
	{
		return $this->metadata->pagination->total;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->metadata->pagination->count;
	}

	/**
	 * @return int
	 */
	public function getPerPage()
	{
		return $this->metadata->pagination->per_page;	
	}

	/**
	 * @return int
	 */
	public function getCurrentPage()
	{
		return $this->metadata->pagination->current_page;	
	}

	/**
	 * @return int
	 */
	public function getTotalPages()
	{
		return $this->metadata->pagination->total_pages;	
	}

	/**
	 * @return string
	 */
	public function getNext()
	{
		return urldecode($this->metadata->pagination->links->next);
	}

	/**
	 * @return string
	 */
	public function getPrevious()
	{
		return urldecode($this->metadata->pagination->links->previous);	
	}
}