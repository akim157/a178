<?php
/*класс для опроса*/
class PollVoterDB extends ObjectDB {
	//объявляем таблицу
	protected static $table = "poll_voters";
	
	public function __construct() {
		//обращаемся к родительскому конструктору и передаем название таблицы
		parent::__construct(self::$table);
		//добавляем свойства и валидторы в объект
		$this->add("poll_data_id", "ValidateID");
		$this->add("ip", "ValidateIP", self::TYPE_IP, $this->getIP());
		$this->add("date", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
	}
	//вернуть общее количество записей 
	public static function getCountOnPollDataID($poll_data_id) {
		return ObjectDB::getCountOnField(self::$table, "poll_data_id", $poll_data_id);
	}
	//голосовал пользователь или не голосовал
	public static function isAlreadyPoll($poll_data_ids) {
		$select = new Select(self::$db);
		$select->from(self::$table, array("id"))
			->whereIn("poll_data_id", $poll_data_ids)
			->where("`ip` = ".self::$db->getSQ(), array(ip2long($_SERVER["REMOTE_ADDR"])))
			->limit(1);
		return (self::$db->selectCell($select))? true: false;
	}
	
}

?>