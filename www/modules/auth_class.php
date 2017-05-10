<?php
/*модуль для авторизации*/
class Auth extends Module {
	
	public function __construct() {
		parent::__construct();
		//куда будет вести форма авторизации
		$this->add("action");
		//системные сообщения
		$this->add("message");
		//ссылка на регистрация
		$this->add("link_register");
		//ссылка на востановление пароля
		$this->add("link_reset");
		//ссылка на восстановление логина
		$this->add("link_remind");
	}
	
	public function getTmplFile() {
		return "auth";
	}
	
}

?>