<?php

/*класс для работы с url*/
class URL {
	
	/*тут будет формировать url на основе переданных параметрах */
	public static function get($action, $controller = "", $data = array(), $amp = true, $address = "", $handler = true) {		
		/*
			action - название action
			controller - название контроллера
			data - массив get параметров
			amp - амперсант & или &amp; (по умолчанию &amp;)
			address - host
			handler - post обработка
		*/
		//проверка если амперсант передан то 
		if ($amp) $amp = "&amp;";
		else $amp = "&";
		//если контроллер есть то формируем uri
		//пример: http://site.tu/user/editprofile (контроллер user и действие редактировать профиль)
		if ($controller) $uri = "/$controller/$action";
		//если нету контроллера просто action
		//пример: http://site.ru/html.html
		else $uri = "/$action";
		//если get параметры переданны
		if (count($data) != 0) {
			//к uri добавляем условие
			$uri .= "?";
			//проводим итерацию для постройки условия формирования uri
			foreach ($data as $key => $value) {
				//ключ значение
				$uri .= "$key=$value".$amp;
			}
			//удаляем последний амперсант
			$uri = substr($uri, 0, -strlen($amp));
		}		
		if ($handler) return self::postHandler($uri, $address);		
		//возвращаем через метод uri
		return self::getAbsolute($address, $uri);
	}
	
	/*метод формирует полный путь*/
	public static function getAbsolute($address, $uri) {
		return $address.$uri;
	}
	
	/*метод для получения текущего url адреса*/
	public static function current($address = "", $amp = false) {
		/*
			address - host
			amp - амперсант
		*/
		
		//формируем полный адрес
		$url = self::getAbsolute($address, $_SERVER["REQUEST_URI"]);
		//если amp true то заменяе знак амперсанта на специальные символы html
		if ($amp) $url = str_replace("&", "&amp;", $url);
		//возвращаем сформированный url
		return $url;
	}
	
	/*метод для получения контроллера и его action
	разбор url адреса, который был запрошен пользователем*/
	public static function getControllerAndAction() {
		//получаем uri который был запрошен
		$uri = $_SERVER["REQUEST_URI"];		
		$uri = UseSEF::getRequest($uri);		
		//если uri нет то возвращаем 404 ошибку
		if (!$uri) return array("Main", "404");
		//присваем пременным значения, дополненного массива
		list($url_part, $qs_part) = array_pad(explode("?", $uri), 2, "");
		//парсим, разбиваем строку в переменные	
		parse_str($qs_part, $qs_vars);
		Request::addSEFData($qs_vars);
		//Main контроллер по умолчанию
		$controller_name = "Main";
		//action по умолчаню index главная страница
		$action_name = "index";
		//проверям если есть get запрос, берем содерижимое до get запроса
		if (($pos = strpos($uri, "?")) !== false) $uri = substr($uri, 0, strpos($uri, "?"));
		//разбиваем uri по / пример: user/edirprofile
		$routes = explode("/", $uri);
		//если есть второе значение (editprofile)
		if (!empty($routes[2])) {
			//если есть и первы то его присваем в контроллер
			if (!empty($routes[1])) $controller_name = $routes[1];
			//а второй в action
			$action_name = $routes[2];
		}
		//если есть только первое значение то его так присваем к action
		elseif (!empty($routes[1])) $action_name = $routes[1];
		//возвращаем имя контроллера и его action
		return array($controller_name, $action_name);
	}
	
	/*удаление из url page пример: ?page = 5*/
	public static function deletePage($url, $amp = true) {
		/*
			url - url адресс
			amp - амперсант
		*/
		
		//обращаемся к методу для 
		return self::deleteGET($url, "page", $amp);
	}
	
	/*формирует page пример: /asdf?id=4&page=*/
	public static function addTemplatePage($url, $amp = true) {		
		//добавляем get параметр page с пустым значением
		return self::addGET($url, "page", "", $amp);
	}
	
	/*метод добавляет get параметр в uri*/
	public static function addGET($url, $name, $value, $amp = true) {
		/*
			url - url адресса
			name - название get параметра
			value - значение get параметра
			amp - амперсант.
		*/
		//если нету get запроса мы его формируем
		if (strpos($url, "?") === false) $url = $url."?".$name."=".$value;		
		//если есть
		else {
			//ставим амперсант
			if ($amp) $amp = "&amp;";
			else $amp = "&";
			$url = $url.$amp.$name."=".$value;
		}
		//возвращаем через метод
		return self::postHandler($url);
	}
	
	/*удалить get параметр*/
	public static function deleteGET($url, $name, $amp = true) {
		/*
			url - url адрес
			name - имя get параметра
			amp - амперсант
		*/
		//заменям амперсант в строке на обычные, чтобы сработала parse_str
		$url = str_replace("&amp;", "&", $url);
		//разбиваем url на массив и присваеваем переменным
		list($url_part, $qs_part) = array_pad(explode("?", $url), 2, "");
		//парсим uri
		parse_str($qs_part, $qs_vars);
		//удаляем значение в массиве get параметра
		unset($qs_vars[$name]);
		//если в uri еще остались параметры
		if (count($qs_vars) != 0) {
			//формируем url Генерирует URL-кодированную строку запроса
			$url = $url_part."?".http_build_query($qs_vars);
			//заменям знак амперсанта
			if ($amp) $url = str_replace("&", "&amp;", $url);
		}
		//если все get параметры удалены
		else $url = $url_part;
		//возвращаем сгенеринованный url
		return self::postHandler($url);
	}
	
	/*метод добавляет слектор id в url запрос*/
	public static function addID($url, $id) {
		return $url."#".$id;
	}
	
	private static function postHandler($uri, $address = "") {		
		$uri = UseSEF::replaceSEF($uri, $address);
		return $uri;
	}
	
}

?>