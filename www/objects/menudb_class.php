<?php
/*класс отвечающий за меню пункты меню*/
class MenuDB extends ObjectDB {
	//объявляем таблицу
	protected static $table = "menu";
	
	public function __construct() {
		//конструктору родительского класса передаем таблицу
		parent::__construct(self::$table);
		//добавляем свойства и валидаторы в объект
		$this->add("type", "ValidateID");
		$this->add("title", "ValidateTitle");
		$this->add("link", "ValidateURL");
		$this->add("parent_id", "ValidateID");
		$this->add("external", "ValidateBoolean");
	}
	//метод для получения верхнего меню
	public static function getTopMenu() {
		/*
			передаем название таблицы
			название класса с помощью, которого будет создан объект
			тип
			константу 
			сортировку по id
		*/
		return ObjectDB::getAllOnField(self::$table, __CLASS__, "type", TOPMENU, "id");
	}
	//метод для получения main меню
	public static function getMainMenu() {
		return ObjectDB::getAllOnField(self::$table, __CLASS__, "type", MAINMENU, "id");
	}
	
}

?>