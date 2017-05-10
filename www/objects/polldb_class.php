<?php
/*класс для опросов*/
class PollDB extends ObjectDB {
	//объявлем таблицу
	protected static $table = "polls";
	
	public function __construct() {
		//передаем название таблицы
		parent::__construct(self::$table);
		//добавляем свойства и валидаторы в объект 
		$this->add("title", "ValidateTitle");
		$this->add("state", "ValidateBoolean", null, 0);
	}
	//загрузка рандомом случайное голосование
	public function loadRandom() {
		//объявляем объект параметрами с БД
		$select = new Select(self::$db);
		//формируем запрос
		$select->from(self::$table, "*")
			->where("`state` = ".self::$db->getSQ(), array(1))
			->rand()
			->limit(1);
		//получам данные строку	
		$row = self::$db->selectRow($select);
		//иницилизируем данные в объект и возвращаем
		return $this->init($row);
	}
	
}

?>