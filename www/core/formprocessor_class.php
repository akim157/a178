<?php
/*класс для проверки данных с форм, обработка форм*/
class FormProcessor {
	
	//поля служит для объекта post и get
	private $request;
	//поле объекта для системных сообщенийк
	private $message;
	
	//конструктор, где параметрамми передается 
	//объект класса Rrequest и Message
	public function __construct($request, $message) {
		//присваем к поле объект
		$this->request = $request;
		$this->message = $message;
	}
	
	public function process($message_name, $obj, $fields, $checks = array(), $success_message = false) {
		/*
			message_name - параметр отвечает за имя переменной, который будет хранить в сесси ошибку данной формы
			obj - объект манипулирувования
			fields - массив имя полей, который будут считанны из request
			checks - массив проверок (сравнениен значений)
			success_message - сообщение, которое будет выводиться пользоватею при правильном вводе данных
		*/
		//ищем исключения
		try {
			//проверка в методе checks если оно возвращает null то возвращаем null
			if (is_null($this->checks($message_name, $checks))) return null;
			//проводим итерацию с полями из request
			foreach ($fields as $field) {
				//проверка на массив //разные параметры приме: password и password_conf
				if (is_array($field)) {
					//присваем массивы пременным
					$f = $field[0];
					$v = $field[1];
					//если в первом переменной есть скобоки ищем метод
					if (strpos($f, "()") !== false) {
						//находим скобки и убираем их
						$f = str_replace("()", "", $f);
						//обращаемся к объекту манипулирования к его методу с параметрами
						$obj->$f($v);
					}//если нет скобок то просто присваем значение
					else $obj->$f = $v;
				} //если поля не массив то просто присваев значение
				else $obj->$field = $this->request->$field;
			}
			//если мы сохранили значение в бд
			if ($obj->save()) {
				//если есть сообщение записываем в сессию это сообщение и название формы
				if ($success_message) $this->setSessionMessage($message_name, $success_message);
				//возвращаем объект
				return $obj;
			}
		} catch (Exception $e) {			
			//если есть икслючение то так же записываем это в сессию
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}
	
	/*метод сравнивания данных*/
	public function checks($message_name, $checks) {
		/*
			message_name - имя сообщения
			checks - массив проверок (сравнениен значений)
		*/
		//ищем исключение
		try {
			//проводим итерацию
			for ($i =0; $i < count($checks); $i++) {
				//если в массиве что есть то это присваеваем к переменной, если нету то булевое значение true, что будет проверятся на эквиавлентность
				$equal = isset($checks[$i][3])? $checks[$i][3]: true;
				//проверям на равенства
				if ($equal && ($checks[$i][0] != $checks[$i][1])) throw new Exception($checks[$i][2]);
				elseif (!$equal && ($checks[$i][0] == $checks[$i][1])) throw new Exception($checks[$i][2]);
			}
			return true;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return null;
		}
	}
	//авторизация пользователя
	public function auth($message_name, $obj, $method, $login, $password) {
		try {
			$user = $obj::$method($login, $password);
			return $user;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return false;
		}
	}
	
	//сохранения в сессиии
	public function setSessionMessage($to, $message) {
		/*
			to - куда будем сохранять
			message - само сообщение
		*/
		//если сессиы не начина, начинаю ее
		if (!session_id()) session_start();
		//сохраняем в сессии
		$_SESSION["message"] = array($to => $message);
	}
	
	//получение из сессии сообщений
	public function getSessionMessage($to) {
		/*
			to - ключ для сообщения сессии
		*/
		//проверка была ли начина сессия если нет то начинаем
		if (!session_id()) session_start();
		//проверяем есть ли в сессии сообщение
		if (!empty($_SESSION["message"]) && !empty($_SESSION["message"][$to])) {
			//если есть присваем переменной
			$message = $_SESSION["message"][$to];
			//удаляем из сессии данное сообщение
			unset($_SESSION["message"][$to]);
			//обращаемся к объекту message класса Message, а именно к его методу get с параметрам сообщения
			//полученный результат возвращаем
			return $this->message->get($message);
		}//если не найдено сообщение возвращаем false
		return false;
	}
	
	/*метод по загрузки изображений*/
	public function uploadIMG($message_name, $file, $max_size, $dir, $source_name = false) {
		/*
			message_name - имя сообщения
			file - название файла
			max_size - максимальный размер файла
			dir - путь к директории куда будет загружаться файла
			source_name - имя файла на сервере
		*/
		//ищем исключение
		try {
			//обращаемся к классу File к его статистическому методу uploadIMG
			$name = File::uploadIMG($file, $max_size, $dir, false, $source_name);
			return $name;
		} catch (Exception $e) {
			$this->setSessionMessage($message_name, $this->getError($e));
			return false;
		}
	}
	//получение ошибок
	private function getError($e) {
		//если исключением является ValidatorException
		if ($e instanceof ValidatorException) {
			$error = current($e->getErrors());
			return $error[0];
		}//если конкретная строка
		elseif (($message = $e->getMessage())) return $message;
		return "UNKNOWN_ERROR";
	}
	
}

?>