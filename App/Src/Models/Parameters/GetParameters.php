<?php

namespace App\Src\Models\Parameters;

use App\Src\Models\Sets\Sets;

class GetParameters extends Parameters {

	protected $sets;

    /**
    * Intializes the parameters object.
    *
    * @param Sets $sets  
    */
    public function __construct(Sets $sets) {
        $this->sets = $sets;
    }

    /**
    * Checks if the required parameters are passed.
    * Extracts all the parameters from the api call
    *
    * @param array $params   
    * @param string $table    
    *
    * @return array         
    */
    protected function extractParameters(array $params, string $table = null) {

        $bindParams = array();
        $params = array_diff_key($params, array('return' => 1));

        foreach ($params as $field => $param) {
            if (is_array($param)) {
                if(count($param) > 2) {
                    throw new \Exception('Parameter array '.preg_replace('/\,\s*\)$/', ')', var_export($param, true)).' can contain maximum two parameters.');
                } else if(count($param) == 2) {
                    if(array_key_exists('operator', $param)) {
                        unset($param['operator']);
                    } else {
                        throw new \Exception('Parameter array '.preg_replace('/\,\s*\)$/', ')', var_export($param, true)).' with two parameters must contain an operator');
                    }
                }

                $this->checkFieldExists(key($param), $table);
                $param = array_values($param)[0];
            } else {
                $this->checkFieldExists($field, $table);
            }

            if(!is_string($param) && !is_numeric($param))
                throw new \Exception('Parameter: '.preg_replace('/\,\s*\)$/', ')', var_export($param, true)).' is not valid.');
            
            $bindParams[] = $param;
        }

        return array_values($bindParams);
    }
}