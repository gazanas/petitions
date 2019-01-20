<?php

namespace App\Src\Models\Parameters;

abstract class Parameters {

	 /**
     * Prepares the parametres to be passed pdo's bindParam
     *
     * @param string $table       
     * @param array  $params        
     *
     * @return array $bind_params   
     * 
     */
    public function prepareParameters(string $table, $params) {
    
        if(is_array($params) === false)
            throw new \Exception('Wrong parameter type passed.');
        
        $params = array_diff_key($params, array('options' => 1, 'condition' => 1));

        $bindParams = $this->extractParameters($params, $table);

        return $bindParams;
    }

    /**
    * Checks if the field exists.
    *
    * @param mixed $field       
    * @param string $table      
    */
    protected function checkFieldExists($field, $table) {
        if(!in_array($field, $this->sets->getColumns($table), true))
            throw new \Exception('Field '.$field.' was not found.');
    }

    protected abstract function extractParameters(array $params, string $table = null);
}