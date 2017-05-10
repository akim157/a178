<?php
/*валидатор для проверки login*/
class ValidateLogin extends Validator {
	/*создаем константы*/
	//максимальная длинна строки
	const MAX_LEN = 100;
	//логин не указан
	const CODE_EMPTY = "ERROR_LOGIN_EMPTY";
	//неверно указан логин
	const CODE_INVALID = "ERROR_LOGIN_INVALID";
	//привышен максимальную длинну строки
	const CODE_MAX_LEN = "ERROR_LOGIN_MAX_LEN";
	
	protected function validate() {
		//берем данные из родительского класса
		$data = $this->data;
		//проверка на указание логина
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		else {
			//проверка на длинну логина
			if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
			//проверка на экранирование логина для это используем метод родительского класса
			elseif ($this->isContainQuotes($data)) $this->setError(self::CODE_INVALID);
		}
	}
	
}

?>