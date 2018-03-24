<?php

if ($_SERVER['argc'] !== 2) {
    die("Missing arguments.\n");
}

$owner = $_SERVER['argv'][1];
$dsn = 'sqlite:constantin.db';

exportData($owner, $dsn);

function exportData($owner, $dsn)
{
    $dir = \sprintf('reports/%s', $owner);
    $path = $dir."/count.json";

    $sql = 'SELECT type,count(*) as nr FROM imported_data where owner=:owner GROUP BY type';

    try {
        $db = new \PDO($dsn);
        $stm = $db->prepare($sql);
        $stm->execute([':owner' => $owner]);

        $result = [];
        while ($item = $stm->fetch(\PDO::FETCH_ASSOC)) {
            $result[\strtolower($item['type'])] = $item['nr'];
        }

        if (!\is_dir($dir)) {
            \mkdir($dir, 0777, true);
        }

        \file_put_contents($path, \json_encode($result, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK));

    } catch (\Exception $exception) {
        echo $exception->getMessage()."\n";
    }
}
