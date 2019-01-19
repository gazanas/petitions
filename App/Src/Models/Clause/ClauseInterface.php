<?php

namespace App\Src\Models\Clause;

interface ClauseInterface {
	
	public function prepareClause(array $params);
}