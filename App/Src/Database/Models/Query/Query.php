<?php

namespace App\Src\Models\Query;

use App\Src\Models\Sets\Sets;

interface Query {
    
    public function initializeQuery(Sets $sets, string $table, array $params);
	
	public function setUpQuery(Sets $sets, string $table, array $params);
}