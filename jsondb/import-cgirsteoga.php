<?php

const FILE_MASK = '*.json';

if ($_SERVER['argc'] !== 2) {
    die("Missing arguments.\n");
}

$dirName = rtrim($_SERVER['argv'][1], DIRECTORY_SEPARATOR);
$dsn = 'sqlite:constantin.db';


if (!\is_dir($dirName)) {
    die(\sprintf("Could not find directory: %s\n", $dirName));
}

if (prepareSqliteDatabase($dsn)) {
    importDataFromDirectory($dirName, $dsn);
}

function importDataFromDirectory($dirName, $dsn)
{
    try {
        $dbConnection = new \PDO($dsn);

        $path = $dirName.DIRECTORY_SEPARATOR.FILE_MASK;
        $i = 0;
        foreach (\glob($path) as $file) {
            (new DataImport($file, $dbConnection))->import();
            \unlink($file);
            $i++;
        }
        echo 'Processed '.$i.' files '."\n";

        $dbConnection = null;
    } catch (\Exception | \Error $exception) {
        echo $exception->getMessage();
    }
}

/**
 * Check database, if not exists create
 * @param string $dns
 * @return bool
 */
function prepareSqliteDatabase($dns)
{
    $connection = \explode(':', $dns);

    if (!isset($connection[1])) {
        echo \sprintf("Invalid connection string: %s\n", $dns);

        return false;
    }

    $database = $connection[1];

    try {
        if (\file_exists($database)) {
            // database exists and assume that has right structure
            return true;
        }

        $db = new \SQLite3($database);

        $fields = DataImport::TABLE_STRUCTURE;
        \array_walk(
            $fields,
            function (&$data, $field) {
                $data = \sprintf('%s %s', $field, $data[DataImport::FIELD_DEFINITION]);
            }
        );

        $sql = \sprintf(
            'CREATE TABLE %s (id INTEGER PRIMARY KEY AUTOINCREMENT, %s );',
            DataImport::TABLE_NAME,
            \join(',', $fields)
        );
        $db->query($sql);
        $db->close();

        return true;

    } catch (\Exception | \Error $exception) {
        echo $exception->getMessage();

        return false;
    }
}

/**
 * Class DataImport
 */
class DataImport
{
    const TABLE_NAME = 'imported_data';
    const FIELD_VALIDATION_RULE = 'rule';
    const FIELD_DEFINITION = 'def';
    const TABLE_STRUCTURE = [
        'date' => [
            self::FIELD_DEFINITION => 'DATE',
            self::FIELD_VALIDATION_RULE => '/^[\d+]{4}-[\d+]{2}-[\d+]{2}$/',
        ],
        'type' => [
            self::FIELD_DEFINITION => 'VARCHAR (100)',
            self::FIELD_VALIDATION_RULE => '/^[A-Z]+$/',
        ],
        'owner' => [
            self::FIELD_DEFINITION => 'VARCHAR (100)',
            self::FIELD_VALIDATION_RULE => '/^[a-z0-9]+$/',
        ],
        'message' => [
            self::FIELD_DEFINITION => 'VARCHAR (500)',
            self::FIELD_VALIDATION_RULE => '',
        ],
    ];

    /** @var \PDO */
    protected $db;
    /** @var array */
    protected $data;
    /** @var string */
    protected $file;

    /**
     * DataImport constructor.
     * @param string $file
     * @param \PDO $dbConnection
     */
    public function __construct($file, $dbConnection)
    {
        $this->file = $file;
        $this->db = $dbConnection;
    }

    /**
     * Process and import file
     */
    public function import()
    {
        $this->readDataFromFile()
            ->filterValidData()
            ->save();
    }

    /**
     * @return $this
     */
    protected function readDataFromFile()
    {
        $this->data = \json_decode(\file_get_contents($this->file), true);

        return $this;
    }

    /**
     * @return $this
     */
    protected function filterValidData()
    {
        $this->data = \array_filter($this->data, [$this, 'isValidEntry']);

        return $this;
    }

    /**
     * @return $this
     */
    protected function save()
    {
        $fieldList = \array_keys(self::TABLE_STRUCTURE);
        $fields = \join(',', $fieldList);
        $placeHolders = \join(
            ',',
            \array_map(
                function ($field) {
                    return \sprintf(':%s', $field);
                },
                $fieldList
            )
        );

        $sql = \sprintf('INSERT INTO %s (%s) VALUES (%s);', self::TABLE_NAME, $fields, $placeHolders);

        $stm = $this->db->prepare($sql);

        foreach ($this->data as $entry) {
            $stm->execute($entry);
        }

        return $this;
    }

    /**
     * @param string $entry
     * @return bool
     */
    public function isValidEntry($entry)
    {
        foreach (self::TABLE_STRUCTURE as $field => $definition) {
            if (!isset($entry[$field])) {
                return false;
            }
            if (!empty($definition[self::FIELD_VALIDATION_RULE])) {
                if (!\preg_match($definition[self::FIELD_VALIDATION_RULE], $entry[$field])) {
                    return false;
                }
            }
        }

        return true;
    }
}
