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
        if (self::$table == null) {
            self::$table = strtolower(get_called_class());
        }
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
    public static function createSql($args=array())
    {
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
            $sql .= self::$table;
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

    public static function getAll($args=array())
    {
        $sql = self::createSql($args);
        $stmt = self::db()->prepareStmt($sql);

        if (isset($args['where'])) {
            foreach ($args['where'] as $w) {
                if (is_array($w)) {
                    $stmt->bindValue($w[0], $w[2]);
                }
            }
        }

        $stmt->execute();
        return self::returnArray($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * New record
     */
    public function save_new()
    {
        $validation = $this->validate();
        if (!$validation['valid']) {
            return array(false, $validation['errors']);
        }

        $sql = 'INSERT INTO ' . self::$table . ' (';
        $vars = get_object_vars($this);
        $keys = array_keys($vars);
        for ($i = 0; $i < count($keys); $i++) {
            if ($keys[$i] == 'changes' || $vars[$keys[$i]] == null) {
                continue;
            }

            $sql .= $keys[$i];
            if ($i < count($keys) - 1) {
                $sql .= ', ';
            }            
        }
        $sql .= ') VALUES (';
        for ($i = 0; $i < count($keys); $i++) {
            if ($keys[$i] == 'changes' || $vars[$keys[$i]] == null) {
                continue;
            }

            $sql .= ':' . $keys[$i];
            if ($i < count($keys) - 1) {
                $sql .= ', ';
            }            
        }
        $sql .= ')';

        $stmt = self::db()->prepareStmt($sql);
        for ($i = 0; $i < count($keys); $i++) {
            if ($keys[$i] == 'changes' || $vars[$keys[$i]] == null) {
                continue;
            }
            $stmt->bindValue($keys[$i], $vars[$keys[$i]]);
        }
        $stmt->execute();

        $this->id = self::db()->getDbHandler()->lastInsertId();
    }

    /**
     * Update record
     */
    public function save()
    {
        $validation = $this->validate();
        if (!$validation['valid']) {
            return array(false, $validation['errors']);
        }

        $vars = get_object_vars($this);

        $sql = 'UPDATE ' . self::$table . ' SET ';
        for ($i = 0; $i < count($this->changes); $i++) {
            $sql .= $this->changes[$i] . ' = :' . $this->changes[$i];
            if ($i < count($this->changes) - 1) {
                $sql .= ', ';
            }            
        }

        $sql .= ' WHERE id = :id';

        $stmt = self::db()->prepareStmt($sql);
        for ($i = 0; $i < count($this->changes); $i++) {
            $stmt->bindValue($this->changes[$i], $vars[$this->changes[$i]]);
        }
        $stmt->bindValue('id', $this->id);
        $stmt->execute();
    }

    public function delete()
    {
        $sql = "DELETE FROM " . self::$table . " WHERE id = :id";
        $stmt = self::db()->prepareStmt($sql);
        $stmt->bindValue('id', $this->id);
        $stmt->execute();
    }

    public function set($col, $val)
    {
        $this->{$col} = $val;

        if (!isset($this->changes)) {
            $this->changes = array();
        }
        $this->changes[] = $col;
    }

    public function get($col)
    {
        return $this->{$col};
    }

    /* Need to be overriden! */
    public function validate()
    {
        return array(
            'valid' => true,
        );
    }
}
