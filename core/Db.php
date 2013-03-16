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
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
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
        return $this->dbh->prepare($sqlStmt);
    }

    public function executeSql($sqlStmt, $bindVal=array())
    {
        $stmt = $this->prepareStmt($sqlStmt);

        if (count($bindVal) > 0) {
            $keys = array_keys($bindVal);
            for ($i = 0; $i < count($bindVal); $i++) {
                $bv = $bindVal[$keys[$i]];
                if (is_array($bv)) {
                    $stmt->bindValue($keys[$i], $bv[0], $bv[1]);
                } else {
                    $stmt->bindValue($keys[$i], $bv);
                }
            }
        }

        $stmt->execute();
        return $stmt;
    }

    public function executeSqlAndFetch($sqlStmt, $bindVal=array(), $all=true)
    {
        $stmt = $this->executeSql($sqlStmt, $bindVal);
        if ($all) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
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
