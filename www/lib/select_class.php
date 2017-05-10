<?php
/*класс адаптер для работы с запросами select родитель AbstractSelect класс формирущие select запросы*/
class Select extends AbstractSelect {
		
	public function __construct() {
		//обращаемся к родительскому классу AbstractSelect и отправляем ему параметры подключение к БД
		//через адаптер класс DataBase и его метода getDBO
		parent::__construct(DataBase::getDBO());
	}
	
}

?>