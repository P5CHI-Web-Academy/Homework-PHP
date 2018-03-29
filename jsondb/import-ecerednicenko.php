<?php

if ($argc != 2) {
    die("Incorrect input\n");
}

$dbc = new PDO('sqlite:cerednicenko.db');

$dbc->exec('CREATE TABLE IF NOT EXISTS `homework` (
      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
      `date` date NOT NULL,
      `type` varchar(24) NOT NULL,
      `owner` varchar(24) NOT NULL,
      `message` varchar(64) NOT NULL
  )'
);

$jsonFiles = [];
$directory = $argv[1];

$fileArr = scandir($directory);
$path = __DIR__ . DIRECTORY_SEPARATOR . $directory . DIRECTORY_SEPARATOR;

foreach ($fileArr as $file) {
    if (preg_match('/\.json/', $file)) {
        $jsonFiles[] = $file;
    }
}

foreach ($jsonFiles as $jsonFile) {
    $anotherOneFile = json_decode(file_get_contents($path . $jsonFile, 'c'));

    if (fileDataMatchesConditions($anotherOneFile)) {
        $query = $dbc->prepare("INSERT INTO `homework` (`date`, `type`, `owner`, `message`) VALUES (:date, :type, :owner, :message);");

        try {
            $query->execute([':date' => $anotherOneFile->date, ':type' => $anotherOneFile->type, ':owner' => $anotherOneFile->owner, ':message' => $anotherOneFile->message]);
        } catch (PDOException  $e) {
            echo $e->getMessage();
            die();
        }
        unlink($path . $jsonFile);
    }

}

function fileDataMatchesConditions($file) {
    return preg_match('/\d{4}\-\d{2}\-\d{2}/', $file->date) &&
    ctype_upper($file->type) &&
    preg_match('/[a-z0-9]+/', $file->owner) &&
    ctype_lower($file->owner) &&
    ctype_upper(substr($file->message, 0, 1));
}