<?php
/*класс для цитат*/
class QuoteDB extends ObjectDB {
	//объявляем таблицу
	protected static $table = "quotes";
	
	public function __construct() {
		//обращаеся к родительскому конструктору передаем парамтром название таблицы
		parent::__construct(self::$table);
		//добавляем свойства в объект
		$this->add("author", "ValidateTitle");
		$this->add("text", "ValidateSmallText");
	}
	//загружаем данные радомом
	public function loadRandom() {
		//создаем объект выборки с параметрами БД
		$select = new Select(self::$db);
		//формируем запрос
		$select->from(self::$table, "*")
			->rand()
			->limit(1);
			//получаем одну строку
		$row = self::$db->selectRow($select);
		//иницилизируем в объект данные и возвращаем
		return $this->init($row);
	}
	
}

?>