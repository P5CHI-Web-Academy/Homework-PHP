<?php

class Count {
    private $dbconnect;
    private $sql_preparing;

    function __construct() {
        try {
            $this->dbconnect = new \PDO('sqlite : alexanderrudnicenco.db');

        }catch (\PDOException $e) {
            echo "Connection failed : " . $e->getMessage();
        }
        $this->sql_preparing = $this->dbconnect->prepare(
                'SELECT COUNT(*) AS numbers , rtype FROM records
                          WHERE owner = :powner GROUP BY rtype ORDER BY "numbers"'
        );
    }

    function count_numbers($owner)
    {
        $this->sql_preparing->execute([':powner' => $owner]);
        $result = $this->sql_preparing->fetchAll();

        if (count($result)) {
            $path = 'report/' . $owner . '/';

            if (!file_exists($path)) {
                if (mkdir($path, 0700, true) and !is_dir($path)) {
                    throw new \RuntimeException('Directory' . $path . 'was not created');
                }
            }
            $out = [];

            foreach ($result as $row) { $out[$row['rtype']] = $row['log_number']; }
            file_put_contents('report/' . $owner . '/counts.json', json_encode($out) . PHP_EOL, FILE_APPEND);
        }
        echo 'Alrigth' . PHP_EOL;
    }
}
$export = new Count();

$export->count_numbers($_SERVER['argv'][1]);