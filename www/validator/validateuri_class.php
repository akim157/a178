<?php
/*валидатор для проверки uri пример: /hren.html*/
class ValidateURI extends Validator {
	//максимальная длинна 
	const MAX_LEN = 255;
	
	protected function validate() {
		//берем данные из родитеского класса
		$data = $this->data;
		//проверка на максимальную длинну
		if (mb_strlen($data) > self::MAX_LEN) $this->setError(self::CODE_UNKNOWN);
		else {
			//создаем переменную с регулярным выражением
			$pattern = "~^(?:/[a-z0-9.,_@%&?+=\~/-]*)?(?:#[^ '\"&<>]*)?$~i";
			//проверка через регулярное вырожение uri
			if (!preg_match($pattern, $data)) $this->setError(self::CODE_UNKNOWN);
		}
	}
	
}

?>