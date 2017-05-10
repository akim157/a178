<?php
/*валидатор для проверки булевских перемен*/
class ValidateBoolean extends Validator {

	protected function validate() {
		//получаем данные от родительского класса Validator
		//пример: checkbox
		$data = $this->data;
		//проверка для булевских значений
		if (($data != 0) && ($data != 1)) $this->setError(self::CODE_UNKNOWN);
	}

}

?>