<?php

namespace Praxis\I10ManutencaoApiSdk;
class RequestData
{
    private $page;    
    private $filters;    
    private $limit;

	/**
	 * @param int $page
	 * @param array $filters
     * @param int $limit
	 */
	public function __construct($page = -1, array $filters = [], $limit = -1)
	{
		$this->page = $page;
        $this->filters = $filters;
        $this->limit = $limit;
	}

	/**
	 * @return int
	 */
	public function getPage()
	{
		return $this->page;
    }
    
    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
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
        $limit = $this->getLimit();

        if ($limit !== -1) {
            $qs[] = sprintf('limit=%s', $limit);
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