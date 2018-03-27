<?php

class Import {

    private $dbc;
    private $prepare_sql;
    private $dir;

    public function __construct(string $dir){
        $this->dir = $dir;
        $this->dbc = new PDO('sqlite:database.db');
        $this->dbc->query('
            CREATE TABLE IF NOT EXISTS records(
              id INTEGER PRIMARY KEY AUTOINCREMENT,
              [rdate] DATE,
              [rtype] VARCHAR(40),
              owner VARCHAR(200),
              message VARCHAR
            )
        ');

        $this->prepare_sql = $this->dbc->prepare("
            INSERT INTO records(rdate, rtype, owner, message)
            VALUES(:date, :type, :owner, :message)
        ");
    }

    public function import_data(){
        $validation = array(
            'date'    => function($arg){return DateTime::createFromFormat('Y-m-d', $arg) !== false;},
            'type'    => function($arg){return ctype_upper($arg);},
            'owner'   => function($arg){return ctype_lower($arg) && ctype_alnum($arg);},
            'message' => true  // Any
        );

        foreach (glob(__DIR__.'/'.$this->dir.'/*.json') as $file) {
            if(($decoded=json_decode(file_get_contents($file), true)) !== null){ //If read successfully
                $error = false; //This flag means, whether all rows in the file are valid
                $data_to_insert = array();
                foreach($decoded as $item){
                    if(!$error=(count(array_intersect_key($validation, $item)) !== count($validation))){
                        $data_to_insert[] = array_combine(array_map(function($v){return ':'.$v;}, array_keys($item)), array_values($item));
                    } else{
                        echo 'Invalid format of value(s)'.PHP_EOL;
                        break;
                    }
                }
                if(!$error){ //Insert rows only if all of them are valid. Otherwise, do nothing
                    foreach ($data_to_insert as $item){
                        $this->prepare_sql->execute($item);
                    }
                    unlink($file);
                }
            } else{
                echo '"'.basename($file).'" cannot be decoded'.PHP_EOL;
            }
        }
    }
}

$importer = new Import($_SERVER['argv'][1]);
$importer->import_data();