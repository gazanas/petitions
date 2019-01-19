<?php

namespace App\Src\Models\Clause;

use App\Src\Models\Sets\Sets;

class QueryOptions {

	protected $sets;
	
	public function __construct(Sets $sets) {
        $this->sets = $sets;
	}

	/**
     * Setup the options of the query
     *
     * @param string $query     
     * @param array  $params    
     *
     * @return string $query    
     *
     */
    public function queryOptions(string $query, array $params) {
        if (isset($params['options']) === true && is_array($params['options']) === true) {
            $this->checkOptions($params['options']);
            foreach ($params['options'] as $option => $value) {
                $option = preg_replace('/(order|ORDER)/', 'order by', $option);
                $query .= ' '.$option.' '.$value;
            }
        }

        return $query;
    }

    /**
     * Checks whether the option exists in the options set
     *
     * @param array $params     
     *
     */
    private function checkOptions(array $params) {
        foreach (array_keys($params) as $option)
            if(in_array($option, $this->sets->getOptionSet(), true) === false)
                throw new \Exception('Option '.$option.' is not correct');
    
        $this->checkOrder($params);
        $this->checkLimit($params);
    }

    /**
     * Checks if order option value is a string
     *
     * @param array $params     
     *
     */
    private function checkOrder(array $params) {
        if(isset($params['order']))
            if(!is_string($params['order']))
                throw new \Exception('Cannot order by '.gettype($params['order']));
    }

    /**
     * Checks if limit option value is an integer
     *
     * @param array $params    
     *
     */
    private function checkLimit(array $params) {
        if(isset($params['limit']))
            if(!is_integer($params['limit']))
                throw new \Exception('Cannot limit by '.gettype($params['limit']));
    }
}
