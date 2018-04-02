<?php
/**
 * Created by PhpStorm.
 */
$dir = $argv[1];
$arr = [];
$arrJSON = [];
$tempCountArr = 0;

/** Создание БД */
try {
    $db = new PDO('sqlite:asircov.db');
    $db->exec("CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        date TEXT, 
        type TEXT,
        owner TEXT,
        message TEXT
    )");
    $insert = $db->prepare("INSERT INTO messages (date, type, owner, message) 
        VALUES (:date, :type, :owner, :message)");

/** Фильтрация .json файлов + получение данных */
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                $cle = preg_match('/.json$/', $file);
                if ($cle == 1) {
                    $arr[$tempCountArr] = '.' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $file;
                    $data = file_get_contents($arr[$tempCountArr]);
                    $decodedJSON = $data ? json_decode($data, true) : null;
                    $arrJSON = is_array($decodedJSON) ? array_merge($arrJSON, $decodedJSON) : $arrJSON;
                    unlink($arr[$tempCountArr]);
                    $tempCountArr++;
                }
            }
            closedir($dh);
        }
    }

    else {
        echo "Unknown error";
        exit;
    }

/** Валидация json данных + запись в БД */
    foreach ($arrJSON as $validator) {
        if( validateDate($validator['date']) &&
            preg_match('/^[A-Z]+$/', $validator['type']) &&
            preg_match('/^[a-z0-9]+$/', $validator['owner'])) {
                $insert->execute([
                    ':date' => $validator['date'],
                    ':type' => $validator['type'],
                    ':owner' => $validator['owner'],
                    ':message' => $validator['message']
                ]);
        }
    }
}

    catch(PDOException $Exception) {
        exit('PDOException : '.$Exception->getMessage() . PHP_EOL);
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $Dtime = DateTime::createFromFormat($format, $date);
        return $Dtime && $Dtime->format($format) == $date;
    }


