<?php

class Counter
{
    private $dbc;
    private $prepare_sql;

    public function __construct()
    {
        $this->dbc         = new PDO('sqlite:db.db');
        $this->prepare_sql = $this->dbc->prepare(
            '
            SELECT 
               COUNT(*) AS log_number,
               rtype
            FROM records
            WHERE owner = :powner
            GROUP BY rtype
            ORDER BY "log_number" DESC
        '
        );
    }

    public function count_logs($owner)
    {
        $this->prepare_sql->execute([':powner' => $owner]);
        $result = $this->prepare_sql->fetchAll();
        if (count($result)) {
            $path = 'report/'.$owner.'/';
            if ( ! file_exists($path)) {
                if ( ! mkdir($path, 0700, true) && ! is_dir($path)) {
                    throw new \RuntimeException(
                        'Directory'.$path.'was not created'
                    );
                }
            }
            $output = [];
            foreach ($result as $row) {
                $output[$row['rtype']] = $row['log_number'];
            }
            file_put_contents(
                'report/'.$owner.'/counts.json',
                json_encode($output).PHP_EOL,
                FILE_APPEND
            );
        }
        echo 'Ok'.PHP_EOL;
    }
}

$exporter = new Counter();
$exporter->count_logs($_SERVER['argv'][1]);
