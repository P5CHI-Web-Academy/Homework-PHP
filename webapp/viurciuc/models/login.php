<?php
/**
 * Created by PhpStorm.
 * User: god
 * Date: 3/28/18
 * Time: 5:19 PM
 */

class login
{
    public $tmp;
    private static $instance;
    public $name;
    private $pass;

    private function __construct()
    {
    }

    /**
     * @return login
     */
    public static function getInstance(): login
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function run(): login
    {
        $this->getUserData();
        $result = $this->getDataFromDB();
        setcookie('auth', $this->name);
        //$this->tmp = $result;
        return $this;
    }

    /**
     * @return login
     */
    public function redirect(): login
    {
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'index.php';
        header("Location: http://$host/$extra");

        return $this;
    }

    /**
     * @return array
     */
    private function getDataFromDB(): array
    {
        $dbh     = new \PDO('sqlite:database.sqlite');
        $prepare = $this->$dbh->prepare('SELECT * FROM users');
        $prepare->execute();

        return $prepare->fetchAll();
    }

    /**
     * @return login
     */
    public function getUserData(): login
    {
        if (isset($_POST['userName']) && ! empty($_POST['userName'])) {
            $this->name
                = $_POST['userName'];
        }

        if (isset($_POST['userPass']) && ! empty($_POST['userPass'])) {
            $this->pass = $_POST['userPass'];
        }

        return $this;
    }

}

var_dump(login::getInstance()->run()->tmp);
