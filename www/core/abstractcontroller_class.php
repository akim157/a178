<?php
/*класс будет служить для всех контроллеров как родительский*/
abstract class AbstractController {
	
	//шиблонизатор
	protected $view;
	//get и post запросы
	protected $request;
	//formprocessor - для работы с формами
	protected $fp = null;
	//авторизованный user, если не авторизованный то null
	protected $auth_user = null;
	//javascript validator для проверки данных форм
	protected $jsv = null;
	
	public function __construct($view, $message) {
		/*
			view - шаблонизатор, объект класса view
			message - системные сообщения для пользователя, объект класса message
		*/
		
		//начинаем сессию если она не была начита
		if (!session_id()) session_start();
		//присваем полю шаблонизатор все зависит от директорий гед лежат tpl файлы
		$this->view = $view;
		//создаем объект get и post запросов класса Rrquest_class
		$this->request = new Request();
		//создаем объект класса FormProcessor_class для проверки данных формы
		//передаем ему два параметра объект request, чтобы он имел доступ ко всем полям, которые были переданны на сервер и message - сообщения системные
		$this->fp = new FormProcessor($this->request, $message);	
		//создаем объект для js валидатора, параметрамми ему передаем системные сообщения	
		$this->jsv = new JSValidator($message);
		//пытаемся получить авторизованного пользователя если он авторизован
		$this->auth_user = $this->authUser();
		//проверка на доступ к данной страницы пользователя
		if (!$this->access()) {
			$this->accessDenied();
			throw new Exception("ACCESS_DENIED");
		}
	}
	
	//обязательный метод в дочерник классов, который должен принимато строку и выводить конечную страницу
	abstract protected function render($str);
	//для допусков
	abstract protected function accessDenied();
	abstract protected function action404();
	
	//проверка usera
	protected function authUser() {
		return null;
	}
	//допуски к странице
	protected function access() {
		return true;
	}
	//метод нельзя переопределить не найдена страница и вывод 404 ошибки
	final protected function notFound() {
		$this->action404();
	}
	//метод нельзя переопределить это редирект на определенный url адрес 
	final protected function redirect($url) {
		header("Location: $url");
		exit;
	}
	
	final protected function renderData($modules, $layout, $params = array()) {
		/*
			modules - принимает набор всех модулей
			layout - главный tpl файл где все расположено
			params - дополнительные параметры
		*/
		//если это не массив
		if (!is_array($modules)) return false;
		//дальше перебираем все модули
		foreach ($modules as $key => $value) {
			$params[$key] = $value;
		}
		//вызываем шаблонизатор, его метод render (true - можно сразу возвращать)
		return $this->view->render($layout, $params, true);
	}
	
}

?>