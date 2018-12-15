<?php

class RequestData
{
	private $page;
	private $filters;

	/**
	 * @param int $page
	 * @param array $filters
	 */
	public function __construct($page = -1, array $filters = [])
	{
		$this->page = $page;
		$this->filters = $filters;
	}

	/**
	 * @return int
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @return array
	 */
	public function getFilters()
	{
		return $this->filters;
	}

	public function __toString()
	{
		$qs = [];

		$page = $this->getPage();

		if ($page !== -1) {
			$qs[] = sprintf('page=%s', $page); 
		}
		$filters = $this->getFilters();
		
		if (! empty($filters)) {
			$qs[] = implode('', array_map(
				function($filterKey) use ($filters) {
					$filter = sprintf(
						'%s:%s', 
						$filterKey, 
						$filters[$filterKey]
					);

					if (end($filters) !== $filters[$filterKey]) {
						$filter .= ';';
					}

					return $filter;
				},
				array_keys($filters)	
			));
		}

		return implode('&', $qs);
	}
}