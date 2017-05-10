<?php
/*класс машрутизатор его главная задача получить контроллер и action
но в классе url вся это задача сделана*/
class Route {
	
	//старт
	public static function start() {
		//получаем имена контроллера и action
		$ca_names = URL::getControllerAndAction();
		//разделяем контроллер приемр: UserController, MainController	
		$controller_name = $ca_names[0]."Controller";
		//название action пример: actionIndex, actionSection, actionEditProfile
		$action_name = "action".$ca_names[1];
		//пытаемня выполнить действия	
		try {
			//если у нас такой класс существует, то мы создаем экземпляр класса
			if (class_exists($controller_name)) $controller = new $controller_name();			
			//если существует метод у объекта $controller, то мы его вызываем 
			//пример: MainController->actionIndex()
			if (method_exists($controller, $action_name)) $controller->$action_name();
			//если возникли проблемы мы генерируем исключение
			//пример: мы не нашли action запрашиваемый пользователям в url то выкидываем исключение в виде 404 ошибки
			else throw new Exception();
		} catch (Exception $e) {
			//вызываем ошибку 404, если доступ не закрыт(доступ формируется в контроллерах)
			if ($e->getMessage() != "ACCESS_DENIED") $controller->action404();
		}
	}
	
}

?>