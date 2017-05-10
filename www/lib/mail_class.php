<?php
/*адаптер класс для работы с mail */
class Mail extends AbstractMail {
	
	public function __construct() {
		/*
		обращаемся к конструктуру родителя, а именно AbstractMail и передаем ему параметры
		первый параметр это объект шаблонизатора класса view c параметрам пути к директории tpl почты
		второй параметр это почта администратора сайта
		*/
		parent::__construct(new View(Config::DIR_EMAILS), Config::ADM_EMAIL);
		//обращаемся к классу AbstractMail, а именно к его методу setFromName
		//этот метод записываем почту админа, параметрами как раз и передаем почту
		$this->setFromName(Config::ADM_NAME);
	}
	
}

?>