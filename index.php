<?php
	header("Content-Type: application/json");
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Method: POST");
	
	include 'db_config.php';
	
	# Если метод GET, выкидываем заглушку
	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		echo "Ooops!!!";
	}
	
	# Если метод POST и есть файлы в запросе
	else if ($_SERVER['REQUEST_METHOD'] == 'POST' and array_key_exists('file', $_FILES)) {
		
		$tel = $_POST['tel'];
				
		$data = json_decode(file_get_contents("php://input"), true);
		$timestamp = date("m-d-Y-His");
		$zipName = "/arc-" . $timestamp . ".zip";
		
		$countfiles = count($_FILES['file']['name']);		
		echo json_encode(array("message" => "Total files " . $countfiles, "status" => "info"));
		
		$upload_path = 'upload/';
		$maxFileSize = 10000000;
		$valid_extensions = array('jpeg', 'jpg', 'png', 'gif');
		
		if( is_dir($upload_path . $tel) === false ) {
			mkdir($upload_path . (string)$tel);
			echo "Create folder";
		}
		else {
			echo "Folder exist";
		}
		
		
		$zip = new ZipArchive();
		$zip -> open(__DIR__ . "/" . $upload_path . $tel . "/" . $zipName, ZipArchive::CREATE|ZipArchive::OVERWRITE);
		
		for ($i=0; $i<$countfiles; $i++) {
			$fileName = $_FILES['file']['name'][$i];
			$tempPath = $_FILES['file']['tmp_name'][$i];
			$fileSize = $_FILES['file']['size'][$i];
			$fileClearName = pathinfo($fileName, PATHINFO_FILENAME);
			$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			
			# Для проверки
			echo json_encode(array("Name" => $fileName, "Temp Path" => $tempPath, "Size" => round($fileSize/1024, 2) . " KB", "Extension" => $fileExt));
			
			# Загрузка файла
			if (in_array($fileExt, $valid_extensions)) {
					# Проверяем размер файла
					if ($fileSize < $maxFileSize) {
						$newFileName = $upload_path . $tel . "/" . $fileClearName . "-" . $timestamp . "." . $fileExt;
						move_uploaded_file($tempPath, $newFileName);
						$query = mysqli_query($conn, 'INSERT INTO tbl_image (name) VALUES("'.$newFileName.'")');
						$zip -> addFile($newFileName, basename($newFileName));
					}
					else{
						echo json_encode(array("message" => "File to large, max file size is " . round($maxFileSize/1000000) . " MB", "status" => "false"));
					}
			}
			else {
				echo json_encode(array("message" => "Wrong file extension", "status" => "false"));
			}
			
		}
		$zip -> close();
	}
	
	else {
		echo json_encode(array("message" => "Что-то пошло не так", "status" => "false"));
	}
?>