<?php
/*валидатор для проверки хэш активации для добавления пользователя в БД*/
class ValidateActivation extends Validator {
	//константа максимальная длинна 100 символов
	const MAX_LEN = 100;
	
	protected function validate() {
		//берем данные из родительского класса 
		$data = $this->data;
		//если длинна строки больше 100 символово
		//то записываем ошибку в родительском классе
		if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_UNKNOWN);
	}
}

?>