<?php
/* Very little PDO Abstraction Layer */
class Db
{
    private $dsn;
    private $dbh;

    private static $instance;
    private static $cfg;

    private function __construct()
    {
        $cfg = self::$cfg;
        $this->dsn = sprintf('%s:host=%s;dbname=%s', $cfg['driver'], $cfg['host'], $cfg['database']);

        $this->dbh = null;
        try {
            $this->dbh = new PDO($this->dsn, $cfg['username'], $cfg['password']);
        } catch (PDOException $e) {
            die($e->getMessage());
            return;
        }
    }

    public function getDbHandler()
    {
        return $this->dbh;
    }

    public function prepareStmt($sqlStmt)
    {
        $stmt = $this->dbh->prepare($sqlStmt);
        return $stmt;
    }

    public function executeSql($sqlStmt, $bindVal=array())
    {
        $stmt = $this->dbh->prepare($sqlStmt);
        $stmt->execute($bindVal); 
        return $stmt;
    }

    public static function loadConfig($cfg)
    {
        self::$cfg = $cfg;
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Db();
        }
        return self::$instance;
    }
}
