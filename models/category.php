<?php namespace Models;

use Application\Registry;
use DAL;

class Category {
	private $category_id = -1;
	private $category_name;
	
	public static function getCategories() {
		return DAL\Factory::newQueryBuilder()->table('yabs_category')->
			orderBy(['category_name ASC'])->query(['category_id', 'category_name'])->
			fetchAll(\PDO::FETCH_CLASS, __CLASS__);
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
	
	public function save() {
		if ($this->category_id < 0) {
			$this->insert();
		} else {
			$this->update();
		}
	}
	
	private function update() {
		DAL\Factory::newQueryBuilder()->table('yabs_category')->
			where('category_id = :category_id', ['category_id' => [$this->category_id, \PDO::PARAM_INT]])->
			update(['category_name' => $this->category_name]);
	}
	
	private function insert() {
		$this->category_id = DAL\Factory::newQueryBuilder()->table('yabs_category')->
			insert(['category_name' => $this->category_name]);
	}
	
	public function delete() {
		Registry::getInstance()->db->beginTransaction();
		try {
			DAL\Factory::newQueryBuilder()->table('yabs_entry')->
				where('category_id = :cat_id', ['cat_id' => [$this->category_id, \PDO::PARAM_INT]])->
				update(['category_id' => [null, \PDO::PARAM_INT]]);
							
			DAL\Factory::newQueryBuilder()->table('yabs_category')->where('category_id = ?', [[$this->category_id, \PDO::PARAM_INT]])->delete();

			Registry::getInstance()->db->commit();
		} catch (\PDOException $e) {
			Registry::getInstance()->db->rollBack();
			throw $e;
		}
	}
}

?>