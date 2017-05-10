<?php
/*класс отвечающий за mail сообщения отправляющийся пользователя*/
abstract class AbstractMail {
	
	/*поля для письма*/
	//тело письма шаблонизатор, который будет обрабатывать tpl файл
	private $view;
	//от куда письмо
	private $from;
	//имя от кого письмо
	private $from_name = "";
	//тип письма
	private $type= "text/html";
	//кодировка письма
	private $encoding = "utf-8";
	
	public function __construct($view, $from) {
		$this->view = $view;
		$this->from = $from;
	}
	//запись адрес письма от куда
	public function setFrom($from) {
		$this->from = $from;
	}
	//звпись имя отпарвителя
	public function setFromName($from_name) {
		$this->from_name = $from_name;
	}
	//запись типа письма
	public function setType($type) {
		$this->type = $type;
	}
	//запись кодировки
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	//метод отправки самого письма
	public function send($to, $data, $template) {
		/*
			to - кому отправляем
			data - массив данных в шаблон
			template - название шаблона, tpl файла
		*/
		//кодировка (от кого) <email отправителя>
		$from = "=?utf-8?B?".base64_encode($this->from_name)."?="." <".$this->from.">";
		//заголовки 
		$headers = "From: ".$from."\r\nReply-To: ".$from."\r\nContent-type: ".$this->type."; charset=\"".$this->encoding."\"\r\n";
		//получить текст письма 
		$text = $this->view->render($template, $data, true);
		//текст парсим, первая строка это тема письма, а все отстальные строки тело письма
		$lines = preg_split("/\\r\\n?|\\n/", $text);
		//тема письма
		$subject = $lines[0];
		//кодировка темы письма
		$subject = "=?utf-8?B?".base64_encode($subject)."?=";		
		$body = "";
		//формируем текст письма
		for ($i = 1; $i < count($lines); $i++) {
			$body .= $lines[$i];
			if ($i != count($lines) - 1) $body .= "\n";
		}
		//если тип html, а не текстовый заменяем \n на br
		if ($this->type = "text/html") $body = nl2br($body);
		//отправляем письмо
		return mail($to, $subject, $body, $headers);
	}
	
}

?>