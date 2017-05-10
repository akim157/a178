<?php
/*класс отвечающий за комментарии*/
class CommentDB extends ObjectDB {
	//объявлем название таблицы в бд от куда будут браться данные
	protected static $table = "comments";
	
	public function __construct() {
		//вызываем родительский конструктор передаем парамметром название таблицы
		parent::__construct(self::$table);
		//добавляем свойства в объект
		$this->add("article_id", "ValidateID");
		$this->add("user_id", "ValidateID");
		$this->add("parent_id", "ValidateID");
		$this->add("text", "ValidateSmallText");
		$this->add("date", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
	}
	//инициализируем данные
	protected function postInit() {
		//формируем ссылку для комментария, чтобы при открытии ссылки то открывался на том месте где расположен комментарий
		$this->link = URL::get("article", "", array("id" => $this->article_id), false, Config::ADDRESS);
		//что будет прокручивать до нужного места
		$this->link = URL::addID($this->link, "comment_".$this->id);
		return true;
	}
	//получить все комментарии в определенной статье
	public static function getAllOnArticleID($article_id) {
		//объявлем метод запроса передаем параметром бд
		$select = new Select(self::$db);
		//формируем запрос
		$select->from(self::$table, "*")
			->where("`article_id` = ".self::$db->getSQ(), array($article_id))
			->order("date");
		//из массива данных формирум массив объектов
		$comments = ObjectDB::buildMultiple(__CLASS__, self::$db->select($select));
		//к каждому комментарию подгрузить данные пользователя кто его добавил
		/*
			первый параметр сам объект
			второй объект класса UserDB
			выходяшее поле пример: $comment->user->login
			поле из таблицы 
		*/
		$comments = ObjectDB::addSubObject($comments, "UserDB", "user", "user_id");
		return $comments;
	}
	//количество комментариев
	public static function getCountOnArticleID($article_id) {
		$select = new Select(self::$db);
		$select->from(self::$table, array("COUNT(id)"))
			->where("`article_id` = ".self::$db->getSQ(), array($article_id));
		return self::$db->selectCell($select);
	}
	//права доступа на редактирования комментариев
	public function accessEdit($auth_user, $field) {
		if ($field == "text") {
			return $this->user_id == $auth_user->id;
		}
		return false;
	}
	//права на удаление комментария
	public function accessDelete($auth_user) {
		return $this->user_id == $auth_user->id;
	}
	
	private static function getAllOnParentID($parent_id) {
		return self::getAllOnField(self::$table, __CLASS__, "parent_id", $parent_id);
	}
	
	protected function preDelete() {
		$comments = CommentDB::getAllOnParentID($this->id);
		foreach ($comments as $comment) {
			try {
				$comment->delete();
			} catch (Exception $e) {
				return false;
			}
		}
		return true;
	}

}

?>