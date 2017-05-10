<?php
/*общий контроллер для всех страниц*/
abstract class Controller extends AbstractController {
	
	//заголовок страницы
	protected $title;
	//content для mega тегов
	protected $meta_desc;
	protected $meta_key;
	//объект для отправки писем
	protected $mail = null;
	//активный url, текущий адрес страницы
	protected $url_active;
	//id открытого раздела
	protected $section_id = 0;
	
	public function __construct() {
		//передаем шаблонизатор, передаем message системные сообщения
		parent::__construct(new View(Config::DIR_TMPL), new Message(Config::FILE_MESSAGES));
		//создаем объект
		$this->mail = new Mail();
		//удалить page берем текущий url
		$this->url_active = URL::deleteGET(URL::current(), "page");
	}
	
	//страница 404
	public function action404() {
		//посылаем заголовок для поисковой оптимизации
		header("HTTP/1.1 404 Not Found");
		header("Status: 404 Not Found");
		//записываем в поля данные
		$this->title = "Страница не найдена - 404";
		$this->meta_desc = "Запрошенная страница не существует.";
		$this->meta_key = "страница не найдена, страница не существует, 404";
		//объект для вывода модуля для системной сообщений
		$pm = new PageMessage();
		$pm->header = "Страница не найдена";
		$pm->text = "К сожалению, запрошенная страница не существует. Проверьте правильность ввода адреса.";
		//отправляем на рендеринг данный модуль
		$this->render($pm);
	}
	//права на доступ
	protected function accessDenied() {
		$this->title = "Доступ закрыт!";
		$this->meta_desc = "Доступ к данной странице закрыт.";
		$this->meta_key = "доступ закрыт, доступ закрыт страница, доступ закрыт страница 403";
		
		$pm = new PageMessage();
		$pm->header = "Доступ закрыт!";
		$pm->text = "У Вас нет прав доступа к данной странице.";
		$this->render($pm);
	}
	//рендеринг (модуль является сам строкой он )
	final protected function render($str) {
		$params = array();
		//узказываем переменные из main.tpl в рендере
		//глова структуры html
		$params["header"] = $this->getHeader();
		//авторизация
		$params["auth"] = $this->getAuth();
		//верхнее меню
		$params["top"] = $this->getTop();		
		//$params["slider"] = $this->getSlider();
		//левая часть сайта
		$params["left"] = $this->getLeft();
		//правая часть сайта
		// $params["right"] = $this->getRight();
		//центральная часть сайта
		$params["center"] = $str;
		//ссылка для поиска
		$params["link_search"] = URL::get("search");
		$this->view->render(Config::LAYOUT, $params);
	}
	//формируем тег <head>
	protected function getHeader() {
		//создаем объект 
		$header = new Header();
		//формируем объект
		//берем из свойства данного объекта
		$header->title = $this->title;
		//вызываем метод meta для формирования meta тегов
		$header->meta("Content-Type", "text/html; charset=utf-8", true);
		$header->meta("description", $this->meta_desc, false);
		$header->meta("keywords", $this->meta_key, false);
		//для отображения корректно на мобильных устройствах
		$header->meta("viewport", "width=device-width", false);
		$header->favicon = "/favicon.ico";
		//сss файлы
		$header->css = array("/css/main.css", "/css/prettify.css");
		//js файлы
		$header->js = array("/js/jquery-1.10.2.min.js", "/js/functions.js", "/js/validator.js", "/js/prettify.js");
		return $header;
	}
	//формируем авторизацию
	protected function getAuth() {
		//проверка на авторизацию
		if ($this->auth_user) return "";
		//создаем объект
		$auth = new Auth();
		//ищем ошибки
		$auth->message = $this->fp->getSessionMessage("auth");
		//текущая страница
		$auth->action = URL::current("", true);
		//ссылка на регистрацию
		$auth->link_register = URL::get("register");
		$auth->link_reset = URL::get("reset");
		$auth->link_remind = URL::get("remind");
		return $auth;
	}
	//верхнее меню
	protected function getTop() {
		//получить пункты верхнеего меню
		$items = MenuDB::getTopMenu();
		//создаем объект
		$topmenu = new TopMenu();
		//uri
		$topmenu->uri = $this->url_active;
		//поля меню
		$topmenu->items = $items;
		return $topmenu;
	}
	
	// protected function getSlider() {
		// $course = new CourseDB();
		// $course->loadOnSectionID($this->section_id, PAY_COURSE);
		// $slider = new Slider();
		// $slider->course = $course;
		// return $slider;
	// }
	
	protected function getLeft() {
		//меню
		//разделы меню
		$items = MenuDB::getMainMenu();
		//объект меню
		$mainmenu = new MainMenu();
		$mainmenu->uri = $this->url_active;
		$mainmenu->items = $items;
		//если пользователь авторизован
		if ($this->auth_user) {
			//создаем панель пользователя
			$user_panel = new UserPanel();
			$user_panel->user = $this->auth_user;
			$user_panel->uri = $this->url_active;
			$user_panel->addItem("Редактировать профиль", URL::get("editprofile", "user"));
			$user_panel->addItem("Выход", URL::get("logout"));
		}
		else $user_panel = "";
		// $poll_db = new PollDB();
		// $poll_db->loadRandom();
		// if ($poll_db->isSaved()) {
			// $poll = new Poll();
			// $poll->action = URL::get("poll", "", array("id" => $poll_db->id));
			// $poll->title = $poll_db->title;
			// $poll->data = PollDataDB::getAllOnPollID($poll_db->id);
		// }
		// else $poll = "";
		// return $user_panel.$mainmenu.$poll;
		return $user_panel.$mainmenu;
	}
	
	// protected function getRight() {
		// $course_db_1 = new CourseDB();
		// $course_db_1->loadOnSectionID($this->section_id, FREE_COURSE);
		// $course_db_2 = new CourseDB();
		// $course_db_2->loadOnSectionID($this->section_id, ONLINE_COURSE);
		// $courses = array($course_db_1, $course_db_2);
		
		// $course = new Course();
		// $course->courses = $courses;
		// $course->auth_user = $this->auth_user;
		
		// $quote_db = new QuoteDB();
		// $quote_db->loadRandom();
		
		// $quote = new Quote();
		// $quote->quote = $quote_db;
		// return $course.$quote;
		
	// }
	//метод для хлеьных крошок
	protected function getHornav() {
		$hornav = new Hornav();
		$hornav->addData("Главная", URL::get(""));
		return $hornav;
	}
	//количество элементов на сранице
	final protected function getOffset($count_on_page) {
		return $count_on_page * ($this->getPage() - 1);
	}
	//вывод страницы по get параметру page=5
	final protected function getPage() {
		$page = ($this->request->page)? $this->request->page: 1;
		if ($page < 1) $this->notFound();
		return $page;
	}
	//вывод блока пагинации
	final protected function getPagination($count_elements, $count_on_page, $url = false) {		
		//общая количестов страниц
		$count_pages = ceil($count_elements / $count_on_page);
		//активная страница
		$active = $this->getPage();
		if (($active > $count_pages) && ($active > 1)) $this->notFound();
		//модуль пагинации
		$pagination = new Pagination();
		//удаляем параметр page
		if (!$url) $url = URL::deletePage(URL::current());
		$pagination->url = $url;		
		$pagination->url_page = URL::addTemplatePage($url);
		$pagination->count_elements = $count_elements;
		$pagination->count_on_page = $count_on_page;
		$pagination->count_show_pages = Config::COUNT_SHOW_PAGES;
		$pagination->active = $active;
		return $pagination;
	}
	
	//авторизация пользователя
	protected function authUser() {
		$login = "";
		$password = "";
		$redirect = false;
		if ($this->request->auth) {
			$login = $this->request->login;
			$password = $this->request->password;
			$redirect = true;
		}
		$user = $this->fp->auth("auth", "UserDB", "authUser", $login, $password);
		if ($user instanceof UserDB) {
			if ($redirect) $this->redirect(URL::current());
			return $user;
		}
		return null;
	}
	
}

?>