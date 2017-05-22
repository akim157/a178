<?php
/*класс отвечающий за пользователей*/
class UserDB extends ObjectDB {
	//объялвет таблицу
	protected static $table = "users";
	//новый пароль
	private $new_password = null;
	
	public function __construct() {
		parent::__construct(self::$table);
//		$this->add("login", "ValidateLogin");
		$this->add("email", "ValidateEmail");
		$this->add("password", "ValidatePassword");
//		$this->add("name", "ValidateName");
		$this->add("avatar", "ValidateIMG");
		$this->add("date_reg", "ValidateDate", self::TYPE_TIMESTAMP, $this->getDate());
		$this->add("activation", "ValidateActivation", null, $this->getKey());
	}
	//запись пароля
	public function setPassword($password) {
		$this->new_password = $password;
	}
	//получение пароля
	public function getPassword() {
		return $this->new_password;
	}
	//загружаем пользователя по email
	public function loadOnEmail($email) {
		return $this->loadOnField("email", $email);
	}
	//загрузка данных по login
	public function loadOnLogin($login) {
		return $this->loadOnField("login", $login);
	}
	//инициализация оброботчик события
	protected function postInit() {
		if (is_null($this->avatar)) $this->avatar = Config::DEFAULT_AVATAR;
		$this->avatar = Config::DIR_AVATAR.$this->avatar;
		return true;
	}
	//для аватарок
	protected function preValidate() {
		if ($this->avatar == Config::DIR_AVATAR.Config::DEFAULT_AVATAR) $this->avatar = null;
		if (!is_null($this->avatar)) $this->avatar = basename($this->avatar);
		if (!is_null($this->new_password)) $this->password = $this->new_password;
		return true;
	}
	//после проверки валидности мы его преобразовываем на новый пароль
	protected function postValidate() {
		if (!is_null($this->new_password)) $this->password = self::hash($this->new_password, Config::SECRET);
		return true;
	}
	//для авторизации
	public function login() {
		//проверка на активацию пользователя
		if ($this->activation != "") return false;
		//запускаем сессию
		if (!session_id()) session_start();
		//записываем в нее логин и пароль
		$_SESSION["auth_login"] = $this->login;
		$_SESSION["auth_password"] = $this->password;
	}
	//для авторизации
	public function email() {
		//проверка на активацию пользователя
		if ($this->activation != "") return false;
		//запускаем сессию
		if (!session_id()) session_start();
		//записываем в нее логин и пароль
		$_SESSION["auth_email"] = $this->email;
		$_SESSION["auth_password"] = $this->password;
	}
	//выход из авторизации
	public function logout() {
		//запускаем сессию если она не была начита
		if (!session_id()) session_start();
		//удалем из сессии переменные
		unset($_SESSION["auth_email"]);
		unset($_SESSION["auth_password"]);
	}
	//получить аватар
	public function getAvatar() {
		$avatar = basename($this->avatar);
		if ($avatar != Config::DEFAULT_AVATAR) return $avatar;
		return null;
	}
	//проверка пароля
	public function checkPassword($password) {
		return $this->password === self::hash($password, Config::SECRET);
	}
	//авторизация пользователя, возвращает объект авторизованного пользователя
	public static function authUser($email = true, $password = false) {
		//если передается логин то мы сразу ставим авторизацию true
		if ($email) $auth = true;
		else {
			//если нет логина значит идет проверка, что находиться в сессии
			//проверяем начата ли сессия
			if (!session_id()) session_start();
			//если логин и пароль в сессии есть то присваеваем знчение в переменные
			if (!empty($_SESSION["auth_email"]) && !empty($_SESSION["auth_password"])) {
				$email = $_SESSION["auth_email"];
				$password = $_SESSION["auth_password"];
			}
			else return;
			$auth = false;
		}
		//создаем объект
		$user = new UserDB();
		//если это была авторизация true,то мы хешируем пароль
		if ($auth) $password = self::hash($password, Config::SECRET);
		//создаем объект выборки
		$select = new Select();
		//формируем запрос ищем по логину и паролю
		$select->from(self::$table, array("COUNT(id)"))
			->where("`email` = ".self::$db->getSQ(), array($email))
			->where("`password` = ".self::$db->getSQ(), array($password));
		//получаем ячейку данных
		$count = self::$db->selectCell($select);
		//если есть такой пользователь
		if ($count) {
			//загружаем пользователя по email
			$user->loadOnEmail($email);
			//если активации нет то исключение
			if ($user->activation != "") throw new Exception("ERROR_ACTIVATE_USER");
			//запускаем
			if ($auth) $user->email();
			return $user;
		}
		//ошибка авторизации user
		if ($auth) throw new Exception("ERROR_AUTH_USER");
	}
	
	public function getSecretKey() {
		return self::hash($this->email.$this->password, Config::SECRET);
	}
	
}

?>