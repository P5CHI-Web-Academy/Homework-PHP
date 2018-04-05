<?php

if ($_SERVER['argc'] != 2) {
  print "Invalid arguments"; exit();
}

$dir = $_SERVER['argv'][1];

try {

  $conn = new \PDO("sqlide:sbogos.db");

  $conn->exec(
    "CREATE TABLE IF NOT EXISTS logs ( 
      id INT(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
      created_at DATE NOT NULL,
      type VARCHAR(255) NOT NULL,
      owner VARCHAR(255) NOT NULL,
      message VARCHAR(255) NOT NULL
    )"
  );

} catch (\PDOException $e) {
  
  print $e->getMessage();
}

if (is_dir($dir)) {

  $files = scandir($dir);

  foreach ($files as $file) {

    if (pathinfo($file)['extension'] == 'json') {

      $records = json_decode(file_get_contents($dir . DIRECTORY_SEPARATOR . $file));

      foreach ($records as $record) {

        if (preg_match('/^[A-Z]+$/', $record->type) && preg_match('/^[a-z0-9]+$/', $record->owner) && DateTime::createFromFormat("Y-m-d", $record->date)) {

          $stmt = $conn->prepare("INSERT INTO logs(created_at, type, owner, message) VALUES (:created_at, :type, :owner, :message)");
          $stmt->execute(['created_at' => $record->date, 'type' => $record->type, 'owner' => $record->owner, 'message' => $record->message]);
        }
      }

      unlink($dir . DIRECTORY_SEPARATOR . $file);
    }
  }
}
