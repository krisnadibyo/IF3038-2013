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

        // Auto (dynamic) getter & setter
        $vars = get_object_vars($this);
        $keys = array_keys($vars);
        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            if (is_array($key)) {
                continue;
            }

            $func = 'get_' . $key;
            $this->{$func} = create_function('$obj', 'return $obj->get(\'' . $key . '\');');
            $func = 'set_' . $key;
            $this->{$func} = create_function('$val,$obj', 'return $obj->set(\'' . $key . '\', $val);');
        }
    }

    public function __call($method, $args)
    {
        if (is_callable($this->$method)) {
            $args['obj'] = $this;
            return call_user_func_array($this->$method, $args);
        } else {
            throw new Exception('Error! Method `' . $method . '` not found in ' . get_class($this), 1);
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
                    $bvkey = preg_replace('/\./', '', $args['where'][$i][0]);
                    $sql .= $args['where'][$i][0] . ' ' . $args['where'][$i][1] . ' :' . $bvkey;
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
    public static function getAll($args=array(), $returnArrayObject=true)
    {
        $sql = self::createSelectSql($args);

        $bv = array();
        if (isset($args['where'])) {
            foreach ($args['where'] as $w) {
                if (is_array($w)) {
                    $bvkey = preg_replace('/\./', '', $w[0]);
                    $bv[$bvkey] = $w[2];
                }
            }
        }

        $rows = self::db()->executeSqlAndFetch($sql, $bv);
        if ($returnArrayObject) {
            return $rows != null ? self::returnArray($rows) : null;
        }

        return $rows;
    }

    /**
     * Fetch one row
     */
    public static function getOne($args=array('limit' => 1), $returnObject=true) {
        $sql = self::createSelectSql($args);

        $bv = array();
        if (isset($args['where'])) {
            foreach ($args['where'] as $w) {
                if (is_array($w)) {
                    $bvkey = preg_replace('/\./', '', $w[0]);
                    $bv[$bvkey] = $w[2];
                }
            }
        }

        $class = get_called_class();
        $row = self::db()->executeSqlAndFetch($sql, $bv, false);
        if ($returnObject) {
            return $row != null ? new $class($row) : null;
        }

        return $row;
    }

    /**
     * Fetch one row by id
     */
    public static function getOneById($id, $returnObject=true) {
        return self::getOne(array(
            'where' => array(array('id', '=', $id)),
            'limit' => 1,
        ), $returnObject);
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
        $arr = array();
        foreach (get_object_vars($this) as $key => $val) {
            if (!is_array($key) && !is_callable($this->{$key}) && $val != null) {
                $arr[$key] = $val;
            }
        }

        return $arr;
    }

    /**
     * New record
     */
    public function save_new($doValidate=true)
    {
        $class = get_called_class();
        if ($doValidate && !$this->validate(true)) {
            throw new Exception('Model validation failed', 1);
            return;
        }

        $sql = 'INSERT INTO ' . $class::$table . ' (';
 
        $vars = get_object_vars($this);
        $keys = array_keys($vars);

        foreach ($keys as $key) {
            if (is_array($key) || is_callable($this->{$key}) || $vars[$key] == null) {
                continue;
            }
            $sql .= $key . ', ';
        }
        $sql = substr($sql, 0, -strlen(', '));

        $sql .= ') VALUES (';
        foreach ($keys as $key) {
            if (is_array($key) || is_callable($this->{$key}) || $vars[$key] == null) {
                continue;
            }
            $bvkey = preg_replace('/\./', '', $key);
            $sql .= ':' . $bvkey . ', ';
        }
        $sql = substr($sql, 0, -strlen(', '));
        $sql .= ')';

        $bv = array();
        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];
            if (is_array($key) || is_callable($this->{$key}) || $vars[$key] == null) {
                continue;
            }

            $bvkey = preg_replace('/\./', '', $key);
            $bv[$bvkey] = $vars[$key];
        }

        // println($sql);
        self::db()->executeSql($sql, $bv);
        $this->id = self::db()->getDbHandler()->lastInsertId();
    }

    /**
     * Update record
     */
    public function save($doValidate=true)
    {
        $class = get_called_class();
        if ($doValidate && !$this->validate(true)) {
            throw new Exception('Model validation failed', 1);
            return;
        }

        $vars = get_object_vars($this);

        $sql = 'UPDATE ' . $class::$table . ' SET ';
        foreach ($this->changes as $change) {
            $sql .= $change . ' = :' . $change . ', ';
        }
        $sql = substr($sql, 0, -strlen(', '));
        $sql .= ' WHERE id = :id';

        $bv = array('id' => $this->id);
        for ($i = 0; $i < count($this->changes); $i++) {
            $bv[$this->changes[$i]] = $vars[$this->changes[$i]];
        }

        // println($sql);
        self::db()->executeSql($sql, $bv);
        unset($this->changes);
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

        return array();
    }
}
