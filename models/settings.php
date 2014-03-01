<?php namespace Models;

/**
 * yabs -  Yet another blog system
 * Copyright (C) 2014 Daniel Triendl <daniel@pew.cc>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

use Application\Registry;
use Application\Exceptions\ValidationException;
use DAL;

class Settings {
	private $data;
	
	private function __construct($data) {
		$this->data = $data;
	}
	
	public static function load() {
		$sth = DAL\Factory::newQueryBuilder()->table('yabs_settings')->query(['setting_name', 'setting_value']);
		$data = array();
		while (($row = $sth->fetch(\PDO::FETCH_ASSOC)) !== false) {
			$data[$row['setting_name']] = $row['setting_value'];
		}
		$c = __CLASS__;
		return new $c($data);
	}
	
	public function save() {
		Registry::getInstance()->db->beginTransaction();
		try {
			DAL\Factory::newQueryBuilder()->table('yabs_settings')->delete();
			
			$sth = DAL\Factory::newQueryBuilder()->table('yabs_settings')->generateInsert(['setting_name', 'setting_value']);
			
			$name = '';
			$value = '';
			$sth->bindParam(':setting_name', $name, \PDO::PARAM_STR, 255);
			$sth->bindParam(':setting_value', $value, \PDO::PARAM_STR, 255);

			foreach ($this->data as $name => $value) {
				$sth->execute();
			}
			Registry::getInstance()->db->commit();
		} catch (\Exception $e) {
			Registry::getInstance()->db->rollBack();
		}
	}
	
	public function __get($name) {
		if (isset($this->data[$name])) {
			return $this->data[$name];
		} else {
			return null;
		}
	}
	
	public function __set($name, $value) {
		if ($value == null && isset($this->data[$name])) {
			unset($this->data[$name]);
		} else{
			$this->data[$name] = $value;
		}
	}
	
	public function getSiteTitle() {
		return $this->site_title;
	}
	
	public function setSiteTitle($value) {
		$this->site_title = $value;
	}
	
	public function getEntriesPerPage() {
		return $this->entries_per_page;
	}
	
	public function setEntriesPerPage($value) {
		$this->entries_per_page = $value;
	}
	
	public function getDateTimeFormat() {
		return $this->datetime_format;
	}
	
	public function setDateTimeFormat($value) {
		$this->datetime_format = $value;
	}
	
	public function getLanguage() {
		return $this->language;
	}
	
	public function setLanguage($value) {
		$this->language = $value;
	}
	
	public function getTemplate() {
		return $this->template;
	}
	
	public function setTemplate($value) {
		$value = str_replace('/', '', $value);
		if (!file_exists(APP_ROOT . '/view/' . $value)) {
			throw new ValidationException('Template not found.');
		}
		$this->template = $value;
	}
	
	public function getAkismet() {
		return $this->akismet;
	}
	
	public function setAkismet($value) {
		$this->akismet = $value; 
	}
	
	public function getAkismetKey() {
		return $this->akismet_key;
	}
	
	public function setAkismetKey($value) {
		$this->akismet_key = $value; 
	}
}

?>