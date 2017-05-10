<?php
/*класс служит для системных сообщений
он будет парсить некий ini файл, будет код сообщения и сам текст сообщения*/
class Message {
	//поле для данных
	private $data;
	//принимаем файл ini
	public function __construct($file) {
		//парсим ini файл где храняться системные сообщения
		$this->data = parse_ini_file($file);
	}
	//метод вытаскавает нужное нам соощение
	public function get($name) {
		/*name - код сообщение*/
		return $this->data[$name];
	}
	
}

?>