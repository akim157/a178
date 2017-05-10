<?php
/*адаптер класс работающий с объектом БД*/
abstract class ObjectDB extends AbstractObjectDB {
	//поля массив где храняться месяцы года
	private static $months = array("янв", "фев", "март", "апр", "май", "июнь", "июль", "авг", "сен", "окт", "ноя", "дек");
	
	public function __construct($table) {
		//в конструктор параметрам приходит название таблицы в БД
		//мы обращаемся к конструктору родителя, а имено AbstractObjectDB
		//и передаем параметры первый название таблицы, второй форматы даты
		parent::__construct($table, Config::FORMAT_DATE);
	}
	//метод получения месяца
	protected static function getMonth($date = false) {
		//проверям есть ли дата если нету то присваем на этот момент
		if ($date) $date = strtotime($date);
		else $date = time();
		//возвращаем месяц
		return self::$months[date("n", $date) - 1];
	}
	//редактирование объекта до
	public function preEdit($field, $value) {
		return true;
	}
	//редактирование объекта после
	public function postEdit($field, $value) {
		return true;
	}
	//методы api
	public function accessEdit($auth_user, $field) {
		return false;
	}
	//методы api
	public function accessDelete($auth_user) {
		return false;
	}
	
}

?>