<?php
/*контроллер для всех страниц, вывод согласно запрошенному методу*/
class MainController extends Controller {
	
	//вывод главной страницы
	public function actionIndex() {
		$this->title = "a178";
		$this->meta_desc = "Запчасти на любой вкус";
		$this->meta_key = "Запчасти, запчасти, разбор";
		
		//вывводим статьи
		$articles = ArticleDB::getAllShow(Config::COUNT_ARTICLES_ON_PAGE, $this->getOffset(Config::COUNT_ARTICLES_ON_PAGE), true);
		//пагинация
		$pagination = $this->getPagination(ArticleDB::getCount(), Config::COUNT_ARTICLES_ON_PAGE, "/");
		//марка авто
		$marka = CatalogDB::getAllShowSearch();
		//блок для центральной части
		$blog = new Blog();
		$blog->articles = $articles;
		$blog->pagination = $pagination;
		$blog->marki = $marka;
		$this->render($this->renderData(array("blog" => $blog), "index"));
	}
	//метод для вывода каталога марок
	public function actionCatalog(){
		$marki = CatalogDB::getAll();
		$this->render($this->renderData(array("catalog" => $marki), "catalog"));
	}
	public function actionMarki(){
		$id = (int) $this->request->id;
		$parts = MarkiDB::getPartsID($id);
		$this->render($this->renderData(array("catalog" => $parts), "catalog"));
	}
	public function actionParts(){
		$id = (int) $this->request->id;
		$sin = (int) $this->request->sin;
		if($sin == 1) {
			$parts = MarkiDB::getPartsAvto($id);
			$json = json_encode ($parts);
			header('Content-type: text/javascript','; charset= utf-8');
			echo $json;
			exit;
		} else {
			$parts = PartsDB::getParts($id);
			$this->render($this->renderData(array("parts" => $parts), "parts"));
		}
	}
	//метод для вывода контактов
	public function actionContact(){
		//вывводим статьи
		$articles = ArticleDB::getAllShow(Config::COUNT_ARTICLES_ON_PAGE, $this->getOffset(Config::COUNT_ARTICLES_ON_PAGE), true);
		//пагинация
		$pagination = $this->getPagination(ArticleDB::getCount(), Config::COUNT_ARTICLES_ON_PAGE, "/");
		//блок для центральной части
		$blog = new Blog();
		$blog->articles = $articles;
		$blog->pagination = $pagination;
		$this->render($this->renderData(array("blog" => $blog),"contact"));
	}
	public function actionSuccess(){
		$blog = new Blog();
		$this->render($this->renderData(array("blog" => $blog),"success"));
	}
	//метод для вывода помощь
	public function actionHelp(){
			//вывводим статьи
//			$help = HelpDB::setVinForm($this->request);
		if($this->request->marka != '') {
			$vin = new VinFormDB();
			$checks[] = array(true, true, "ERROR_PASSWORD_CURRENT");
			$message_fields_profession = $fields = array("marka",
				                               "model",
				                               "year",
			                                   "vin",
											   "body_type",
			                                   "drive_type",
			                                   "door",
				                               "drive_unit",
											   "air_conditioning",
											   "hydraulic_booster",
				                               "turbo",
			                                   "engine_capacity",
				                               "type_kpp",
				                               "infa_dop",
				                               "part_name",
			                                   "part_article",
				                               "part_count",
				                               "part_note",
				                               "fio",
				                               "city",
				                               "phone",
				                               "email",
											   "date");
			$data = $this->fp->process($message_fields_profession, $vin, $fields, $checks , "SUCCESS_CREATE_PROFESSION");
			$this->mail->send(Config::ADM_EMAIL, array('data' => $data), "vinform");
			$this->redirect(URL::get("success"));
		}
		else {
			$articles = ArticleDB::getAllShow(Config::COUNT_ARTICLES_ON_PAGE, $this->getOffset(Config::COUNT_ARTICLES_ON_PAGE), true);
			//пагинация
			$pagination = $this->getPagination(ArticleDB::getCount(), Config::COUNT_ARTICLES_ON_PAGE, "/");
			//блок для центральной части
			$blog = new Blog();
			$blog->articles = $articles;
			$blog->pagination = $pagination;
			$this->render($this->renderData(array("blog" => $blog),"help"));
		}

//		$form = new Form();
////		$form->hornav = $hornav;
//		$form->header = "Регистрация";
//		$form->name = "register";
//		$form->action = URL::current();
//		$form->message = $this->fp->getSessionMessage($message_name);
////		$form->text("name", "Имя и/или фамилия:", $this->request->name);
////		$form->text("login", "Логин:", $this->request->login);
//		$form->text("email", "E-mail:", $this->request->email);
//		$form->password("password", "Пароль:");
//		$form->password("password_conf", "Подтвердите пароль:");
//		$form->captcha("captcha", "Введите код с картинки:");
//		$form->submit("Регистрация");
//
////		$form->addJSV("name", $this->jsv->name());
////		$form->addJSV("login", $this->jsv->login());
//		$form->addJSV("email", $this->jsv->email());
//		$form->addJSV("password", $this->jsv->password("password_conf"));
//		$form->addJSV("captcha", $this->jsv->captcha());

//		$marki = CatalogDB::getAll(false);
//		$years = array();
//		for($i=1; $i<30; $i++){
//			$year = date('Y') - $i;
//			$years[$i]['id'] = $year;
//			$years[$i]['name'] = $year;
//		}
//		$body_type = array(
//			'Седан',
//			'Хэтчбэк',
//			'Универсал',
//			'Джип',
//			'Купе',
//			'Кабриолет',
//			'Минивэн',
//			'Микроавтобус'
//		);
//		$i = 0;
//		foreach($body_type as $val){
//			$body_types[$i]['id'] = $val;
//			$body_types[$i]['name'] = $val;
//			$i++;
//		}
//		$doors = array();
//		for($i=2; $i<8; $i++) {
//			$doors[$i]['id'] = $i;
//			$doors[$i]['name'] = $i;
//		}
//		$drive = array('Передний', 'Задний', 'Полный');
//		for($i=0; $i<3; $i++){
//			$drivers[$i]['id'] = $i;
//			$drivers[$i]['name'] = $drive[$i];
//		}
//		$form = new Form();
////		$form->hornav = $hornav;
//		$form->header = "Форма VIN-запроса";
//		$form->name = "vin_query";
//		$form->action = URL::current();
////		$form->message = $this->fp->getSessionMessage($message_name);
//		$form->select('marki','Выберите марку автомабиля', $marki);
//		$form->text('model','Модель', $this->request->model);
//		$form->select('years','Укажите год', $years);
//		$form->text('vin','VIN-код', $this->request->vin);
//		$form->text('type','Тип/буквы двигателя', $this->request->type);
//		$form->select('body_types','Тип кузова', $body_type);
//		$form->select('door','Число дверей', $doors);
//		$form->select('drive_unit','Привод', $drivers);
//		$form->password("password_reset", "Новый пароль:");
//		$form->password("password_reset_conf", "Повторите пароль:");
//		$form->submit("Далее");
//
//		$form->addJSV("password_reset", $this->jsv->password("password_reset_conf"));
//		$this->render($form);
	}
	//метод для вывода формы регистрации
	public function actionRegister(){
		$message_name = "register";
		if ($this->request->register) {
			$user_old_1 = new UserDB();
			$user_old_1->loadOnEmail($this->request->email);
//			$captcha = $this->request->captcha;
//			$checks = array(array(Captcha::check($captcha), true, "ERROR_CAPTCHA_CONTENT"));
			$checks = array(array(true, true, "ERROR_CAPTCHA_CONTENT"));
			$checks[] = array($this->request->password, $this->request->password_conf, "ERROR_PASSWORD_CONF");
			$checks[] = array($user_old_1->isSaved(), false, "ERROR_EMAIL_ALREADY_EXISTS");
			$user = new UserDB();
			$fields = array("email", array("setPassword()", $this->request->password),"roles");
			$user = $this->fp->process($message_name, $user, $fields, $checks);
			if ($user instanceof UserDB) {
				$this->mail->send($user->email, array("user" => $user, "link" => URL::get("activate", "", array("email" => $user->email, "key" => $user->activation), false, Config::ADDRESS)), "register");
				$this->redirect(URL::get("sregister"));
			}
		}

			//вывводим статьи
			$articles = ArticleDB::getAllShow(Config::COUNT_ARTICLES_ON_PAGE, $this->getOffset(Config::COUNT_ARTICLES_ON_PAGE), true);
			//пагинация
			$pagination = $this->getPagination(ArticleDB::getCount(), Config::COUNT_ARTICLES_ON_PAGE, "/");
			//блок для центральной части
			$blog = new Blog();
			$blog->articles = $articles;
			$blog->pagination = $pagination;
			$this->render($this->renderData(array("blog" => $blog),"register"));
	}
	//метод для вывода разделов
	public function actionSection() {
		//создаем объект 
		$section_db = new SectionDB();
		//загружаем с id раздела
		$section_db->load($this->request->id);
		//проверка на сохранение, если такого id раздела нет 
		if (!$section_db->isSaved()) $this->notFound();
		//сохраняем в объект
		$this->section_id = $section_db->id;
		//сохраняем заголовок
		$this->title = $section_db->title;
		$this->meta_desc = $section_db->meta_desc;
		$this->meta_key = $section_db->meta_key;
		//вызываем хлебные крошки
		$hornav = $this->getHornav();
		//добавляем данные в хлебные крошки текст
		$hornav->addData($section_db->title);
		//модуль для введения раздела
		$intro = new Intro();
		//передаем туда хлебные крошки
		$intro->hornav = $hornav;
		$intro->obj = $section_db;
		
		//создаем объект блока для раздела
		$blog = new Blog();
		//получаем все статьи по данному разделу
		$articles = ArticleDB::getAllOnPageAndSectionID($this->request->id, Config::COUNT_ARTICLES_ON_PAGE);		
		$pagination = $this->getPagination(ArticleDB::getCountSection($this->request->id), Config::COUNT_ARTICLES_ON_PAGE, "/html");				
		//все остальные статьи
		$more_articles = ArticleDB::getAllOnSectionID($this->request->id, false);
		//получаем сдвиг для избежания повторов статей
		$i = 0;
		foreach ($more_articles as $id => $article) {
			$i++;
			unset($more_articles[$id]);
			if ($i == Config::COUNT_ARTICLES_ON_PAGE) break;
		}
		//в объекте сохраняем статью
		$blog->articles = $articles;
		$blog->pagination = $pagination;
		//сохраняем список статей
		$blog->more_articles = $more_articles;
		//соединям как строки
		$this->render($intro.$blog);
	}
	//категории для разделов
	public function actionCategory() {
		$category_db = new CategoryDB();
		$category_db->load($this->request->id);
		if (!$category_db->isSaved()) $this->notFound();
		$this->section_id = $category_db->section_id;
		$this->title = $category_db->title;
		$this->meta_desc = $category_db->meta_desc;
		$this->meta_key = $category_db->meta_key;
		
		$section_db = new SectionDB();
		$section_db->load($category_db->section_id);
		
		$hornav = $this->getHornav();
		$hornav->addData($section_db->title, $section_db->link);
		$hornav->addData($category_db->title);
		
		$intro = new Intro();
		$intro->hornav = $hornav;
		$intro->obj = $category_db;
		
		$pagination = $this->getPagination(ArticleDB::getCount(), Config::COUNT_ARTICLES_ON_PAGE, "/");	
		
		$category = new Category();		
		$articles = ArticleDB::getAllOnCatID($this->request->id, Config::COUNT_ARTICLES_ON_PAGE);
				
		$category->articles = $articles;		
		
		$this->render($intro.$category);
	}
	//вывод статьи
	public function actionArticle() {
		$article_db = new ArticleDB();
		$article_db->load($this->request->id);
		if (!$article_db->isSaved()) $this->notFound();
		$this->title = $article_db->title;
		$this->meta_desc = $article_db->meta_desc;
		$this->meta_key = $article_db->meta_key;
		
		$hornav = $this->getHornav();
		
		if ($article_db->section) {
			$this->section_id = $article_db->section->id;
			$hornav->addData($article_db->section->title, $article_db->section->link);
			$this->url_active  = URL::get("section", "", array("id" => $article_db->section->id));
		}
		if ($article_db->category) {
			$hornav->addData($article_db->category->title, $article_db->category->link);
			$this->url_active  = URL::get("category", "", array("id" => $article_db->category->id));
		}
		
		$hornav->addData($article_db->title);
		
		$prev_article_db = new ArticleDB();
		$prev_article_db->loadPrevArticle($article_db);
		$next_article_db = new ArticleDB();
		$next_article_db->loadNextArticle($article_db);
		
		$article = new Article();
		$article->hornav = $hornav;
		$article->auth_user = $this->auth_user;
		$article->article = $article_db;
		if ($prev_article_db->isSaved()) $article->prev_article = $prev_article_db;
		if ($next_article_db->isSaved()) $article->next_article = $next_article_db;
		
		$article->link_register = URL::get("register");
		//комментарии
		$comments = CommentDB::getAllOnArticleID($article_db->id);
		$article->comments = $comments;
		$this->render($article);
	}
	//метод для опроса
	public function actionPoll() {
		$message_name = "poll";
		if ($this->request->poll) {
			$poll_voter_db = new PollVoterDB();
			$poll_data = PollDataDB::getAllOnPollID($this->request->id);
			$already_poll = PollVoterDB::isAlreadyPoll(array_keys($poll_data));
			$checks = array(array($already_poll, false, "ERROR_ALREADY_POLL"));
			$this->fp->process($message_name, $poll_voter_db, array("poll_data_id"), $checks, "SUCCESS_POLL");
			$this->redirect(URL::current());
		}
		$poll_db = new PollDB();
		$poll_db->load($this->request->id);
		if (!$poll_db->isSaved()) $this->notFound();
		$this->title = "Результаты голосования: ".$poll_db->title;
		$this->meta_desc = "Результаты голосования: ".$poll_db->title.".";
		$this->meta_key = "результаты голосования, ".mb_strtolower($poll_db->title);
		
		$poll_data = PollDataDB::getAllDataOnPollID($poll_db->id);
		
		$hornav = $this->getHornav();
		$hornav->addData($poll_db->title);
		
		$poll_result = new PollResult();
		$poll_result->hornav = $hornav;
		$poll_result->message = $this->fp->getSessionMessage($message_name);
		$poll_result->title = $poll_db->title;
		$poll_result->data = $poll_data;
		
		$this->render($poll_result);
		
	}
	//метод для регистрации
//	public function actionRegister() {
//		$message_name = "register";
//		//регистрация
//		if ($this->request->register) {
//			$user_old_1 = new UserDB();
//			$user_old_1->loadOnEmail($this->request->email);
//			$user_old_2 = new UserDB();
//			$user_old_2->loadOnLogin($this->request->login);
//			$captcha = $this->request->captcha;
//			$checks = array(array(Captcha::check($captcha), true, "ERROR_CAPTCHA_CONTENT"));
//			$checks[] = array($this->request->password, $this->request->password_conf, "ERROR_PASSWORD_CONF");
//			$checks[] = array($user_old_1->isSaved(), false, "ERROR_EMAIL_ALREADY_EXISTS");
//			$checks[] = array($user_old_2->isSaved(), false, "ERROR_LOGIN_ALREADY_EXISTS");
//			$user = new UserDB();
//			$fields = array("name", "login", "email", array("setPassword()", $this->request->password));
//			$user = $this->fp->process($message_name, $user, $fields, $checks);
//			if ($user instanceof UserDB) {
//				$this->mail->send($user->email, array("user" => $user, "link" => URL::get("activate", "", array("login" => $user->login, "key" => $user->activation), false, Config::ADDRESS)), "register");
//				$this->redirect(URL::get("register"));
//			}
//		}
//		//отображение страницы регистрации
//		$this->title = "Регистрация на сайте ".Config::SITENAME;
//		$this->meta_desc = "Регистрация на сайте ".Config::SITENAME.".";
//		$this->meta_key = "регистрация сайт ".mb_strtolower(Config::SITENAME).", зарегистрироваться сайт ".mb_strtolower(Config::SITENAME);
////		$hornav = $this->getHornav();
////		$hornav->addData("Регистрация");
//		//модуль для формы
//		$form = new Form();
////		$form->hornav = $hornav;
//		$form->header = "Регистрация";
//		$form->name = "register";
//		$form->action = URL::current();
//		$form->message = $this->fp->getSessionMessage($message_name);
////		$form->text("name", "Имя и/или фамилия:", $this->request->name);
////		$form->text("login", "Логин:", $this->request->login);
//		$form->text("email", "E-mail:", $this->request->email);
//		$form->password("password", "Пароль:");
//		$form->password("password_conf", "Подтвердите пароль:");
//		$form->captcha("captcha", "Введите код с картинки:");
//		$form->submit("Регистрация");
//
////		$form->addJSV("name", $this->jsv->name());
////		$form->addJSV("login", $this->jsv->login());
//		$form->addJSV("email", $this->jsv->email());
//		$form->addJSV("password", $this->jsv->password("password_conf"));
//		$form->addJSV("captcha", $this->jsv->captcha());
//
//		$this->render($form);
//
//	}
	//страница регистарции
	public function actionSRegister() {
		$this->title = "Регистрация на сайте ".Config::SITENAME;
		$this->meta_desc = "Регистрация на сайте ".Config::SITENAME.".";
		$this->meta_key = "регистрация сайт ".mb_strtolower(Config::SITENAME).", зарегистрироваться сайт ".mb_strtolower(Config::SITENAME);
	
//		$hornav = $this->getHornav();
//		$hornav->addData("Регистрация");
		
		$pm = new PageMessage();
//		$pm->hornav = $hornav;
		$pm->header = "Регистрация";
		$pm->text = "Учётная запись создана. На указанный Вами адрес электронной почты отправлено письмо с инструкцией по активации. Если письмо не доходит, то обратитесь к администрации.";
		$this->render($pm);
	}
	//активация пользователя через ссылку
	public function actionActivate() {
		$user_db = new UserDB();
		//загружаем пользователя по полученному логину
		$user_db->loadOnEmail($this->request->email);
		
//		$hornav = $this->getHornav();
		//если есть такой пользователь существует и поле пустое то пользователь актевирован уже
		if ($user_db->isSaved() && ($user_db->activation == "")) {
			$this->title = "Ваш аккаунт уже активирован";
			$this->meta_desc = "Вы можете войти в свой аккаунт, используя Ваши логин и пароль.";
			$this->meta_key = "активация, успешная активация, успешная активация регистрация";
//			$hornav->addData("Активация");
		}
		//если ключи не подходят, то выводим ошибку
		elseif ($user_db->activation != $this->request->key) {
			$this->title = "Ошибка при активации";
			$this->meta_desc = "Неверный код активации! Если ошибка будет повторяться, то обратитесь к администрации.";
			$this->meta_key = "активация, ошибка активация, ошибка активация регистрация";
//			$hornav->addData("Ошибка активации");
		}
		//активация
		else {
			$user_db->activation = "";		
			try {
			//сохраняем пользователя в бд					
				$user_db->save();
			} catch (Exception $e) {print_r($e->getMessage());}			
			$this->title = "Ваш аккаунт успешно активирован";
			$this->meta_desc = "Теперь Вы можете войти в свою учётную запись, используя Ваши логин и пароль.";
			$this->meta_key = "активация, успешная активация, успешная активация регистрация";
//			$hornav->addData("Активация");
		}
		
		$pm = new PageMessage();
//		$pm->hornav = $hornav;
		$pm->header = $this->title;
		$pm->text = $this->meta_desc;
		$this->render($pm);
	}
	
	public function actionLogout() {
		UserDB::logout();
		$this->redirect($_SERVER["HTTP_REFERER"]);
	}
	//метод для востановления пароля пользователя
	public function actionReset() {
		$message_name = "reset";
		$this->title = "Восстановление пароля";
		$this->meta_desc = "Восстановление пароля пользователя.";
		$this->meta_key = "восстановление пароля, восстановление пароля пользователя";
		$hornav = $this->getHornav();
		$hornav->addData("Восстановление пароля");
		if ($this->request->reset) {
			$user_db = new UserDB();
			$user_db->loadOnEmail($this->request->email);
			if ($user_db->isSaved()) $this->mail->send($user_db->email, array("user" => $user_db, "link" => URL::get("reset", "", array("email" => $user_db->email, "key" => $user_db->getSecretKey()), false, Config::ADDRESS)), "reset");
			$pm = new PageMessage();
			$pm->hornav = $hornav;
			$pm->header = "Восстановление пароля";
			$pm->text = "Инструкция по восстановлению пароля выслана на указанный e-mail адрес.";
			$this->render($pm);
		}
		elseif ($this->request->key) {
			$user_db = new UserDB();
			$user_db->loadOnEmail($this->request->email);
			if ($user_db->isSaved() && ($this->request->key === $user_db->getSecretKey())) {
				if ($this->request->reset_password) {
					$checks = array(array($this->request->password_reset, $this->request->password_reset_conf, "ERROR_PASSWORD_CONF"));
					$user_db = $this->fp->process($message_name, $user_db, array(array("setPassword()", $this->request->password_reset)), $checks);
					if ($user_db instanceof UserDB) {
						$user_db->login();
						$this->redirect(URL::get("sreset"));
					}
				}
				$form = new Form();
				$form->hornav = $hornav;
				$form->header = "Восстановление пароля";
				$form->name = "reset_password";
				$form->action = URL::current();
				$form->message = $this->fp->getSessionMessage($message_name);
				$form->password("password_reset", "Новый пароль:");
				$form->password("password_reset_conf", "Повторите пароль:");
				$form->submit("Далее");
				
				$form->addJSV("password_reset", $this->jsv->password("password_reset_conf"));
				$this->render($form);
			}
			else {
				$pm = new PageMessage();
				$pm->hornav = $hornav;
				$pm->header = "Неверный ключ";
				$pm->text = "Попробуйте ещё раз, если ошибка будет повторяться, то обратитесь к администрации.";
				$this->render($pm);
			}
		}
		else {
			$form = $this->getFormEmail("Восстановление пароля", "reset", $message_name);
			$form->hornav = $hornav;
			$this->render($form);
		}
	}
	
	public function actionSReset() {
		$this->title = "Восстановление пароля";
		$this->meta_desc = "Восстановление пароля успешно завершено.";
		$this->meta_key = "восстановление пароля, восстановление пароля пользователя, восстановление пароля пользователя завершено";
		
		$hornav = $this->getHornav();
		$hornav->addData("Восстановление пароля");
		
		$pm = new PageMessage();
		$pm->hornav = $hornav;
		$pm->header = "Пароль успешно изменён!";
		$pm->text = "Теперь Вы можете войти на сайт, если Вы не авторизовались автоматически.";
		
		$this->render($pm);
	}
	//востановление логина
	public function actionRemind() {
		$this->title = "Восстановление логина";
		$this->meta_desc = "Восстановление логина пользователя.";
		$this->meta_key = "восстановление логина, восстановление логина пользователя";
		$hornav = $this->getHornav();
		$hornav->addData("Восстановление логина");
		if ($this->request->remind) {
			$user_db = new UserDB();
			$user_db->loadOnEmail($this->request->email);
			if ($user_db->isSaved()) $this->mail->send($user_db->email, array("user" => $user_db), "remind");
			$pm = new PageMessage();
			$pm->hornav = $hornav;
			$pm->header = "Восстановление логина";
			$pm->text = "Письмо с Вашим логином отправлено на указанный e-mail адрес.";
			$this->render($pm);
		}
		else {
			$form = $this->getFormEmail("Восстановление логина", "remind", "remind");
			$form->hornav = $hornav;
			$this->render($form);
		}
	}
	//поиск по сайту 
	public function actionSearch() {
		$hornav = $this->getHornav();
		$hornav->addData("Поиск");
		$this->title = "Поиск: ".$this->request->query;
		$this->meta_desc = "Поиск ".$this->request->query.".";
		$this->meta_key = "поиск, поиск ".$this->request->query;
		$articles = ArticleDB::search($this->request->query);
		$sr = new SearchResult();
		if (mb_strlen($this->request->query) < Config::MIN_SEARCH_LEN) $sr->error_len = true;
		$sr->hornav = $hornav;
		$sr->field = "full";
		$sr->query = $this->request->query;
		$sr->data = $articles;
		$this->render($sr);
	}
	//расширенный поиск
	public function actionExtesearch(){
		if($this->request->model){
			$parts = PartsDB::getParts($this->request->model);
		}
		else {
			$parts = MarkiDB::getPartsID($this->request->marka);
		}
		
		$sr = new Extesearch();
		if (mb_strlen($this->request->query) < Config::MIN_SEARCH_LEN) $sr->error_len = true;
		$sr->field = "full";
		$sr->query = '';
		$sr->data = $parts;
		$this->render($sr);
	}
	//получения формы для email
	private function getFormEmail($header, $name, $message_name) {
		$form = new Form();
		$form->header = $header;
		$form->name = $name;
		$form->action = URL::current();
		$form->message = $this->fp->getSessionMessage($message_name);
		$form->text("email", "Введите e-mail, указанный при регистрации:", $this->request->email);
		$form->submit("Далее");
		$form->addJSV("email", $this->jsv->email());
		return $form;
	}
	
}

?>