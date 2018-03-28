<?php

class Import {
    private $dbconnect;
    private $sql_preparing;
    private $dirname;

    # В конструкторе подключаем базу данных и вносим данные в таблицу
    function __construct(string $dirname) {
        $this->dirname = $dirname;

        try {
            $this->dbconnect = new \PDO('sqlite:alexanderrudnicenco.db');

        }catch (\PDOException $e) {
            echo "Connection failed : " . $e->getMessage();
        }

        $this->dbconnect->query(
            'CREATE TABLE IF NOT EXISTS records(
                        id INTEGER PRIMARY KEY AUTOINCREMENT,
                        [rdate] DATE,
                        [rtype] VARCHAR(40),
                        owner VARCHAR(250),
                        message VARCHAR(250)
            )'
        );

        $this->sql_preparing = $this->dbconnect->prepare(
            "INSERT INTO records(rdate, rtype, owner, message) VALUES (:date , :type, :owner, :message)"
        );
    }

    function importing_data() {
        $validation = [
            'date' => function($argument) { return DateTime::createFromFormat('Y-m-d', $argument) !== false; },
            'type' => function($argument) { return ctype_upper($argument); },
            'owner'=> function($argument) { return ctype_lower($argument) and ctype_alnum($argument); },
            'message' => true
        ];
        foreach (glob(__DIR__.'/'.$this->dirname.'/*.json') as $file) {
           if (( $decode = json_decode(file_get_contents($file), true)) !== null) {
               $errors = false;
               $data = []; # Массив с данными для вставки

               foreach ($decode as $value) {
                   if (!$errors = (count(array_intersect_key($validation, $value))) !== count($validation)) {
                       $data = array_combine(array_map(
                           function ($v) { return ':' . $v; }, array_keys($value)),
                           array_values($validation)
                       );
                   } else {
                       echo 'Invalid format of values'. PHP_EOL;
                       break;
                   }
               }
               if (!$errors) {
                   foreach ($data as $value) {
                       $this->sql_preparing->execute($value);
                   }
                   unlink($file);
               }
               else {
                   echo '"'. basename($file) . '" cannot be decoded' . PHP_EOL;
               }
           }
        }
    }
}

$import = new Import($_SERVER['argv'][1]);

$import->importing_data();