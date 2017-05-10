<?php
/*валидатор для проверки пароля*/
class ValidatePassword extends Validator {
	//минимальная длинна
	const MIN_LEN = 6;
	//максимальная длинна
	const MAX_LEN = 100;
	//пароль не задан
	const CODE_EMPTY = "ERROR_PASSWORD_EMPTY";
	//пароль неодекватный 
	const CODE_CONTENT = "ERROR_PASSWORD_CONTENT";
	//минимальная длинна пароля
	const CODE_MIN_LEN = "ERROR_PASSWORD_MIN_LEN";
	//макимальная длинна пароля
	const CODE_MAX_LEN = "ERROR_PASSWORD_MAX_LEN";
	
	protected function validate() {
		//берем значение с родительского класса
		$data = $this->data;
		//если данные нету
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		else {
			//если пароль меньше минимальной длинны
			if (mb_strlen($data) < self::MIN_LEN) $this->setError(self::CODE_MIN_LEN);
			//если пароль больше максимальной длинны
			elseif (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
			//проверка на одекватный пароль через регулярное вырожение
			elseif (!preg_match("/^[a-z0-9_]+$/i", $data)) $this->setError(self::CODE_CONTENT);
		}
	}
	
}

?>