<?php
/*валидатор для email адреса тут нужно вывводить результат пользователю*/
class ValidateEmail extends Validator {
	/*создаем константы*/
	//максимальная длинна строки
	const MAX_LEN = 100;
	//email не указан
	const CODE_EMPTY = "ERROR_EMAIL_EMPTY";
	//email указан не верный (не подходит формат)
	const CODE_INVALID = "ERROR_EMAIL_INVALID";
	//email привысил максимальную длинну
	const CODE_MAX_LEN = "ERROR_EMAIL_MAX_LEN";
	
	protected function validate() {
		//берем данные с родительского класса
		$data = $this->data;
		//проверка если email не указан то записываем ошибку
		if (mb_strlen($data) == 0) $this->setError(self::CODE_EMPTY);
		else {
			//если длинна строки больше максимальной
			if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_MAX_LEN);
			else {
				//сохраняем в переменной ругулярное вырожение для проверки email
				$pattern = "/^[a-z0-9_][a-z0-9\._-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+$/i";
				//если email не прошел регулярную проверку то записываем шибку
				if (!preg_match($pattern, $data)) $this->setError(self::CODE_INVALID);
			}
		}
	}
	
}

?>