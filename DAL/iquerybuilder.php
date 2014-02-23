<?php namespace DAL;

interface IQueryBuilder {
	function table($table);
	function leftJoin($table, $on);
	function where($selection, array $selectionArgs);
	
	function query(array $columns);
	function limit($limit, $offset = 0);
	function orderBy(array $orderColumns);
	
	function delete();
	
	function update(array $contentValues);
	
	function insert(array $contentValues);
	
	//SQL functions
	function concat(array $values);
	function isNull($first, $second);
}

?>