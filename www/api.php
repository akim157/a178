<?php
	//запускает api
	require_once "start.php";
	//для передачи запросов
	$request = new Request();
	$api = new API();
	$result = false;
	if ($request->func == "edit") $result = $api->edit($request->obj, $request->value, $request->name, $request->type);
	elseif ($request->func == "delete") $result = $api->delete($request->obj, $request->id);
	//вызываем addCommint
	elseif ($request->func == "add_comment") $result = $api->addComment($request->parent_id, $request->article_id, $request->text);
	//формируем ответ
	if ($result !== false) echo json_encode(array("r" => $result, "e" => false));
	else echo json_encode(array("r" => false, "e" => true));
?>