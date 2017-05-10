<?php
/*класс для провеки id (6,9,8)*/
class ValidateIDs extends Validator {
	
	protected function validate() {
		$data = $this->data;
		if (is_null($data)) return;
		//если не соотвествует регулярному вырожению то неизвестная ошибка
		if (!preg_match("/^\d+(,\d+)*\d?$/", $data)) $this->setError(self::CODE_UNKNOWN);
	}
	
}

?>