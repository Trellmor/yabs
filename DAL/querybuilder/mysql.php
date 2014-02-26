<?php namespace DAL\QueryBuilder;

class MySQL extends QueryBuilder implements IQueryBuilder {
	function concat(array $values) {
		return 'CONCAT(' . implode(', ', $values) . ')';
	}
}

?>