<?php
/*класс для опросов*/
class PollDataDB extends ObjectDB {
	//объявляем таблицу
	protected static $table = "poll_data";
	
	public function __construct() {
		//обращаемся к конструктуру и параметром передаем название таблицы
		parent::__construct(self::$table);
		//добавляем свойства и валидаторы в объект
		$this->add("poll_id", "ValidateID");
		$this->add("title", "ValidateTitle");
	}
	//загрузка всех вариантов ответов для одноко конретного опроса
	public static function getAllOnPollID($poll_id) {
		return ObjectDB::getAllOnField(self::$table, __CLASS__, "poll_id", $poll_id, "id");
	}
	//возвращает с нужной сортировкой
	public static function getAllDataOnPollID($poll_id) {
		$poll_data = self::getAllOnPollID($poll_id);
		//получам количество голосов
		foreach ($poll_data as $pd) {
			$pd->voters = PollVoterDB::getCountOnPollDataID($pd->id);
		}
		//сортируем
		uasort($poll_data, array(__CLASS__, "compare"));
		return $poll_data;
	}
	//метод сортировки
	private static function compare($value_1, $value_2) {
		return $value_1->voters < $value_2->voters;
	}
	
}

?>