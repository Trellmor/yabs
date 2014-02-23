<?php namespace Models;

use DAL;

class Category {
	private $category_id;
	private $category_name;
	
	public static function getCategories() {
		$qb = DAL\Factory::newQueryBuilder();
		return $qb->table('yabs_category')->query(['category_id', 'category_name'])->fetchAll(\PDO::FETCH_CLASS, __CLASS__);
	}	
	
	public static function getCategory($categoryId) {
		return DAL\Factory::newQueryBuilder()->table('yabs_category')->
			where('category_id = ?', [[$categoryId, \PDO::PARAM_INT]])->
			query(['category_id', 'category_name'])->fetchObject(__CLASS__);
	}	
	
	public function getId() {
		return $this->category_id;
	}
	
	public function getName() {
		return $this->category_name;
	}
	
	public function setName($value) {
		$this->category_name = $value;
	}
}

?>