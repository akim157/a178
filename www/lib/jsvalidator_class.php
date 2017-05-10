<?php
/*класс для проверки данных из форм*/
class JSValidator {
	//создаем поле 
	private $message;
	
	public function __construct($message) {
		/*message - сообщения для полльзователя из класса Message*/
		$this->message = $message;
	}
	//пароль
	public function password($f_equal = false, $min_len = true, $t_empty = false) {
		//получаем класс и начинаем его заполнять
		$cl = $this->getBase();
		//проверка на минимальное заполнение
		if ($min_len) {
			//свойству объекта присваем значения класса для проверки пароля обращаемого к константе
			$cl->min_len = ValidatePassword::MIN_LEN;
			//тут передаем свойству из объекты message полученного в конструкторе
			$cl->t_min_len = $this->message->get(ValidatePassword::CODE_MIN_LEN);
		}
		//также к свойту максимальное заполнение приравниваем константу из класса Validate
		$cl->max_len = ValidatePassword::MAX_LEN;
		//к свойству максиальная длинна сообщение присваем из объекта message параметром в котором передается константа класса валидатора
		$cl->t_max_len = $this->message->get(ValidatePassword::CODE_MAX_LEN);
		//если данные не пусты
		if ($t_empty) $cl->t_empty = $this->message->get($t_empty);
		//если пусты
		else $cl->t_empty = $this->message->get(ValidatePassword::CODE_EMPTY);
		//если есть нет совпадение
		if ($f_equal) {
			$cl->f_equal = $f_equal;
			$cl->t_equal = $this->message->get("ERROR_PASSWORD_CONF");
		}
		return $cl;
	}
	//имя
	public function name($t_empty = false, $t_max_len = false, $t_type = false) {
		return $this->getBaseData($t_empty, $t_max_len, $t_type, "ValidateName", "name");
	}
	//логин
	public function login($t_empty = false, $t_max_len = false, $t_type = false) {
		return $this->getBaseData($t_empty, $t_max_len, $t_type, "ValidateLogin", "login");
	}
	//почта
	public function email($t_empty = false, $t_max_len = false, $t_type = false) {
		return $this->getBaseData($t_empty, $t_max_len, $t_type, "ValidateEmail", "email");
	}
	//автар
	public function avatar() {
		//получаем объект класса
		$cl = $this->getBase();
		//свойства приравниваем сообщение об отсутсвии аватраки
		$cl->t_empty = $this->message->get("ERROR_AVATAR_EMPTY");
		//возвращаем объект
		return $cl;
	}
	//капча
	public function captcha() {
		//получаем объект класса
		$cl = $this->getBase();
		//сообщение об отсутсвии капчи
		$cl->t_empty = $this->message->get("ERROR_CAPTCHA_EMPTY");
		return $cl;
	}
	
	private function getBaseData($t_empty, $t_max_len, $t_type, $class, $type) {
		//получаем объект класса и начинаем его заполнять
		$cl = $this->getBase();
		//тип из параметра тип
		$cl->type = $type;
		//максимальная длинна так же из параметра
		$cl->max_len = $class::MAX_LEN;
		
		//проверка если данные не пусты
		if ($t_empty) $cl->t_empty = $this->message->get($t_empty);
		//если данные пусты
		else $cl->t_empty = $this->message->get($class::CODE_EMPTY);
		if ($t_max_len) $cl->t_max_len = $this->message->get($t_max_len);
		else $cl->t_max_len = $this->message->get($class::CODE_MAX_LEN);
		if ($t_type) $cl->t_type = $this->message->get($t_type);
		else $cl->t_type = $this->message->get($class::CODE_INVALID);
		return $cl;
	}
	//создает новый встроенный в php класс пустой и добавлять определнные свойства
	private function getBase() {
		//создаем объект встроенного в php класса
		$cl = new stdClass();
		//свойтва объекта тип (email, дата и т.д.)
		$cl->type = "";
		//минимальная длинна у конкрентного поля
		$cl->min_len = "";
		//максимальная длинна
		$cl->max_len = "";
		//за текст, которое будет выводиться пользователя если он указал слишком короткое поле
		$cl->t_min_len = "";
		//за текст, которое будет выводиться пользователя если он указал слишком длинное поле
		$cl->t_max_len = "";
		//пустое поле
		$cl->t_empty = "";
		//текст, который выводится при не корректном заполнеии данного поля
		$cl->t_type = "";
		//данное свойство отвечает за то поле, которое текущеие поле должно иметь одинаковое значение
		$cl->f_equal = "";
		//если поле не совподают значениями выводитеся данное сообщение
		$cl->t_equal = "";
		return $cl;
	}

}

?>