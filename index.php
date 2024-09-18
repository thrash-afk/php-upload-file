<?php
	header("Content-Type: application/json");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Method: POST");
	
	# Если метод GET, выкидываем заглушку
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		echo "Ooops!!!";
	}
	
	# Если метод POST и есть файлы в запросе
	else if ($_SERVER['REQUEST_METHOD'] == 'POST' and sizeof($_FILES) > 0) {
	 $data = json_decode(file_get_contents("php://input"), true);
	
		echo "Массив " . sizeof($_FILES['file']) . "\n";	# удалить
	
		$countfiles = count($_FILES['file']['name']);		# удалить
		echo "Всего файлов " . $countfiles . "\n";			# удалить
		print_r($_FILES);									# удалить
	}
	
	else {
		echo json_encode(array("message" => "Что-то пошло не так", "status" => "false"));
	}
?>