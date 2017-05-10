<?php
/*адаптер класс работы с бд*/
class DataBase extends AbstractDataBase {
	//позволяет делать только одно подключение к бд
	private static $db;
	//метод для получинеия объекта БД
	public static function getDBO() {
		//если объекь БД не был еще создан то мы его создаем.
		//создаем объект и отправляем параметры констркутору AbstractDatabase
		//первый параметр localhost, имя user бд и т.д.
		if (self::$db == null) self::$db = new DataBase(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME, Config::DB_SYM_QUERY, Config::DB_PREFIX);
		//возвращаем объект
		return self::$db;
	}	
}

?>