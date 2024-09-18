<?php
	header("Content-Type: application/json");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Method: POST");
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		echo "Ooops!!!";
	}
	else if ($_SERVER['REQUEST_METHOD'] == 'POST' and sizeof($_FILES) > 0) {
	 $data = json_decode(file_get_contents("php://input"), true);
	
		echo "Массив " . sizeof($_FILES['file']) . "\n";
	
		$countfiles = count($_FILES['file']['name']);
		echo "Всего файлов " . $countfiles . "\n";
		echo "POST!\n";
		print_r($_FILES);
	}
	else {
		echo json_encode(array("message" => "Что-то пошло не так", "status" => "false"));
	}
?>