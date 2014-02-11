<?php namespace DAL;

class MySQL extends QueryBuilder implements IQueryBuilder {
	function concat(array $values) {
		return 'CONCAT(' . implode(', ', $values) . ')';
	}
}

?>