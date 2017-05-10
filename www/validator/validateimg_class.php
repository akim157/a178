<?php
/*валидатор для проверки изображений */
class ValidateIMG extends Validator {
	
	protected function validate() {
		//получаем данные от родителя
		$data = $this->data;
		//проверка если данные не null и имеют формат file.jpg то все ок.
		if (!is_null($data) && !preg_match("/^[a-z0-9-_]+\.(jpg|jpeg|png|gif)$/i", $data)) $this->setError(self::CODE_UNKNOWN);
	}
	
}

?>