<?php
/*контроллер для работы с редактированием пользователя*/
class UserController extends Controller {
	//метод вывода страницы редактирования профиля
	public function actionEditProfile() {
		//загрузка аватарки
		$message_avatar_name = "avatar";
		//смена имени
		$message_name_name = "name";
		//смена пароля
		$message_password_name = "password";
		//изменение аватарки
		if ($this->request->change_avatar) {
			$img = $this->fp->uploadIMG($message_avatar_name, $_FILES["avatar"], Config::MAX_SIZE_AVATAR, Config::DIR_AVATAR);
			if ($img) {
				$tmp = $this->auth_user->getAvatar();
				$obj = $this->fp->process($message_avatar_name, $this->auth_user, array(array("avatar", $img)), array(), "SUCCESS_AVATAR_CHANGE");
				//удалем старое изображение
				if ($obj instanceof UserDB) {
					if ($tmp) File::delete(Config::DIR_AVATAR.$tmp);
					$this->redirect(URL::current());
				}
			}
		}
		//изменение имени пользователя
		elseif ($this->request->change_name) {
			$checks = array(array($this->auth_user->checkPassword($this->request->password_current_name), true, "ERROR_PASSWORD_CURRENT"));
			$user_temp = $this->fp->process($message_name_name, $this->auth_user, array("name"), $checks, "SUCCESS_NAME_CHANGE");
			if ($user_temp instanceof UserDB) $this->redirect(URL::current());
		}
		elseif ($this->request->change_password) {
			$checks = array(array($this->auth_user->checkPassword($this->request->password_current), true, "ERROR_PASSWORD_CURRENT"));
			$checks[] = array($this->request->password, $this->request->password_conf, "ERROR_PASSWORD_CONF");
			$user_temp = $this->fp->process($message_password_name, $this->auth_user, array(array("setPassword()", $this->request->password)), $checks, "SUCCESS_PASSWORD_CHANGE");
			if ($user_temp instanceof UserDB) {
				$this->auth_user->login();
				$this->redirect(URL::current());
			}
		}
		
		$this->title = "Редактирование профиля";
		$this->meta_desc = "Редактирование профиля пользователя.";
		$this->meta_key = "редактирование профиля, редактирование профиля пользователя, редактирование профиля пользователя сайт";
		//объект для формы изменения профиля
		$form_avatar = new Form();
		$form_avatar->name = "change_avatar";
		$form_avatar->action = URL::current();
		$form_avatar->enctype = "multipart/form-data";
		$form_avatar->message = $this->fp->getSessionMessage($message_avatar_name);
		$form_avatar->file("avatar", "Аватар:");
		$form_avatar->submit("Сохранить");
		$form_avatar->class = "input";
		
		$form_avatar->addJSV("avatar", $this->jsv->avatar());
		//форма для смены имени
		$form_name = new Form();
		$form_name->name = "change_name";
		$form_name->header = "Изменить имя";
		$form_name->action = URL::current();
		$form_name->message = $this->fp->getSessionMessage($message_name_name);
		//поля файл
		$form_name->text("name", "Ваше имя:", $this->auth_user->name);
		$form_name->password("password_current_name", "Текущий пароль");
		$form_name->submit("Сохранить");
		//проверка полей
		$form_name->addJSV("name", $this->jsv->name());
		$form_name->addJSV("password_current_name", $this->jsv->password(false, false, "ERROR_PASSWORD_CURRENT_EMPTY"));
		//форма для смена пароля		
		$form_password = new Form();
		$form_password->name = "change_password";
		$form_password->header = "Изменить пароль";
		$form_password->action = URL::current();
		$form_password->message = $this->fp->getSessionMessage($message_password_name);
		$form_password->password("password", "Новый пароль");
		$form_password->password("password_conf", "Повторите пароль");
		$form_password->password("password_current", "Текущий пароль");
		$form_password->submit("Сохранить");
		
		//проверки полей формы
		$form_name->addJSV("password", $this->jsv->password("password_conf"));
		$form_name->addJSV("password_current", $this->jsv->password(false, false, "ERROR_PASSWORD_CURRENT_EMPTY"));
		$hornav = $this->getHornav();
		$hornav->addData("Редактирование профиля");
		
		$this->render($this->renderData(array("hornav" => $hornav, "form_avatar" => $form_avatar, "form_name" => $form_name, "form_password" => $form_password), "profile", array("avatar" => $this->auth_user->avatar, "max_size" => (Config::MAX_SIZE_AVATAR / KB_B))));
	}
	//только авторизованный пользователь иметь доступ к данному контролеру
	protected function access() {
		if ($this->auth_user) return true;
		return false;
	}
	
}

?>