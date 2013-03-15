<?php
class Model
{
    protected $id = null;
    private static $db = null;
    protected static $table = null;

    public function __construct($row)
    {
        $keys = array_keys($row);
        for ($i = 0; $i < count($keys); $i++) {
            $this->{$keys[$i]} = $row[$keys[$i]];
        }
    }

    public static function load()
    {
        self::$db = Db::getInstance();
    }

    protected static function db()
    {
        return self::$db;
    }

    protected static function returnArray($rows)
    {
        $class = get_called_class();

        $returnArray = array();
        foreach ($rows as $row) {
            $returnArray[] = new $class($row);
        }

        return $returnArray;
    }

    /**
     * args:
     *   'select': array of string,
     *   'from': string,
     *   'where': array consisting either:
     *     'OR', or 'AND', or array(<col>, <sign>, <val>)
     *   'limit': integer,
     *   'offset': integer,
     *   'orderBy': string
     */
    public static function createSelectSql($args=array())
    {
        $class = get_called_class();
        $sql = 'SELECT ';

        // SELECT [...]
        if (isset($args['select'])) {
            for ($i = 0; $i < count($args['select']); $i++) {
                $select = $args['select'][$i];
                $sql .= $select;

                if ($i != count($args['select']) - 1) {
                    $sql .= ', ';
                }
            }
        } else {
            $sql .= '*';
        }

        $sql .= ' FROM ';

        // FROM [...]
        if (isset($args['from'])) {
            $sql .= $args['from'];
        } else {
            $sql .= $class::$table;
        }

        // WHERE
        if (isset($args['where'])) {
            $sql .= ' WHERE ';

            for ($i = 0; $i < count($args['where']); $i++) {
                if (is_array($args['where'][$i])) {
                    $sql .= $args['where'][$i][0] . ' ' . $args['where'][$i][1] . ' :' . $args['where'][$i][0];
                } else {
                    $sql .= $args['where'][$i];
                }
                if ($i != count($args['where']) - 1) {
                    $sql .= ' ';
                }
            }
        }

        // ORDER BY
        if (isset($args['orderBy'])) {
            $sql .= ' ORDER BY ' . $args['orderBy'];
        }

        // LIMIT
        if (isset($args['limit'])) {
            $sql .= ' LIMIT ' . $args['limit'];
        }

        // OFFSET
        if (isset($args['offset'])) {
            $sql .= ' OFFSET ' . $args['offset'];
        }

        return $sql;
    }

    /**
     * Fetch many rows
     */
    public static function getAll($args=array())
    {
        $sql = self::createSelectSql($args);

        $bv = array();
        if (isset($args['where'])) {
            foreach ($args['where'] as $w) {
                if (is_array($w)) {
                    $bv[$w[0]] = $w[2];
                }
            }
        }

        $rows = self::db()->executeSqlAndFetch($sql, $bv);
        return $rows != null ? self::returnArray($rows) : null;
    }

    /**
     * Fetch one row
     */
    public static function getOne($args=array('limit' => 1)) {
        $sql = self::createSelectSql($args);

        $bv = array();
        if (isset($args['where'])) {
            foreach ($args['where'] as $w) {
                if (is_array($w)) {
                    $bv[$w[0]] = $w[2];
                }
            }
        }

        $class = get_called_class();
        $row = self::db()->executeSqlAndFetch($sql, $bv, false);
        return $row != null ? new $class($row) : null;        
    }

    /**
     * Example:
     * Model::deleteWhere('id = :id AND name = :name', array('id' => 0, 'name' => 'jack'));
     */
    public static function deleteWhere($where=null, $bindVal=array())
    {
        $class = get_called_class();
        if (!$where) {
            return;
        }

        $sql = 'DELETE FROM ' . $class::$table . ' WHERE ' . $where;
        self::db()->executeSql($sql, $bindVal);
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * New record
     */
    public function save_new()
    {
        $class = get_called_class();
        if (!$this->validate(true)) {
            return;
        }

        $sql = 'INSERT INTO ' . $class::$table . ' (';
        $vars = get_object_vars($this);
        $keys = array_keys($vars);

        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            if ($key == 'changes' || $vars[$key] == null) {
                continue;
            }

            $sql .= $key;
            if ($i < count($keys) - 1) {
                $sql .= ', ';
            }            
        }

        $sql .= ') VALUES (';
        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            if ($key == 'changes' || $vars[$key] == null) {
                continue;
            }

            $sql .= ':' . $key;
            if ($i < count($keys) - 1) {
                $sql .= ', ';
            }
        }
        $sql .= ')';

        $bv = array();
        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            if ($key == 'changes' || $vars[$key] == null) {
                continue;
            }

            $bv[$key] = $vars[$key];
        }

        self::db()->executeSql($sql, $bv);
        $this->id = self::db()->getDbHandler()->lastInsertId();
    }

    /**
     * Update record
     */
    public function save()
    {
        $class = get_called_class();
        if (!$this->validate(true)) {
            return;
        }

        $vars = get_object_vars($this);

        $sql = 'UPDATE ' . $class::$table . ' SET ';
        for ($i = 0; $i < count($this->changes); $i++) {
            $sql .= $this->changes[$i] . ' = :' . $this->changes[$i];
            if ($i < count($this->changes) - 1) {
                $sql .= ', ';
            }            
        }

        $sql .= ' WHERE id = :id';
        
        $bv = array('id' => $this->id);
        for ($i = 0; $i < count($this->changes); $i++) {
            $bv[$this->changes[$i]] = $vars[$this->changes[$i]];
        }

        self::db()->executeSql($sql, $bv);
    }

    /**
     * Delete record
     */
    public function delete()
    {
        $class = get_called_class();
        $sql = "DELETE FROM " . $class::$table . " WHERE id = :id";
        self::db()->executeSql($sql, array('id' => $this->id));
    }

    /**
     * Set a col value
     */
    public function set($col, $val)
    {
        $this->{$col} = $val;

        if (!isset($this->changes)) {
            $this->changes = array();
        }
        $this->changes[] = $col;
    }

    /**
     * Get a col value
     */
    public function get($col)
    {
        return $this->{$col};
    }

    /* Need to be overriden! */
    public function validate($boolReturn=false)
    {
        if ($boolReturn) {
            return true;
        }

        return array(
            'valid' => true,
        );
    }
}
