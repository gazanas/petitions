<?php

namespace App\Src\Models\Parameters;

use App\Src\Models\Sets\Sets;

class UpdateParameters extends Parameters {

	protected $sets;

    /**
    * Intializes the parameters object.
    *
    * @param $sets   
    */
    public function __construct(Sets $sets) {
        $this->sets = $sets;
    }
	
    /**
    * Checks if the required parameters are passed.
    * Extracts all the parameters from the api call
    *
    * @param array $params        
    *
    * @return array         
    */
    protected function extractParameters(array $params, string $table = null) {
        $bindParams = [];
        
        $this->matchRequired($params);

        $bindParams[] = $params['updated'];
        $params = array_diff_key($params, array('to_update' => 1, 'updated' => 1));

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

    /**
     *   Finds the parameters that are necessary for the API call and
     *   check if these exist in the parameters passed by the user, if not then throws a Required Exception
     *
     * @param array  $params    
     *
     */
    private function matchRequired(array $params) {

        if (array_key_exists('to_update', $params) === false)
            throw new \Exception('Missing Required Field (to_update)');
    
        if(array_key_exists('updated', $params) === false) 
            throw new \Exception('Missing Required Field (updated)');
    }

}