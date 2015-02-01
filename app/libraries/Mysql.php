<?php

final class Mysql
{
    public $conn = false;

    private static $instance;

    public function escape($str)
    {
        return $this->conn->real_escape_string($str);
    }

    private function refValues($arr)
    {
        if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
        {
            $refs = array();
            foreach($arr as $key => $value)
            {
                $refs[$key] = &$arr[$key];
            }

            return $refs;
        }

        return $arr;
    }

    public function __construct()
    {

    }

    public function addServer($server)
    {
        $this->conn = new mysqli($server['host'], $server['login'], $server['password'], $server['dbname']);

        if($this->conn->connect_error)
        {
            return false;
        }

        $this->conn->set_charset('utf8');
        return true;
    }

    public function prepare($query)
    {
        $stmt = $this->conn->prepare($query);

        if($this->conn->error)
        {
            return false;
        }

        return $stmt;
    }

    public function insert($query, $type, $params)
    {
        $stmt = $this->prepare($query);

        array_unshift($params, $type);

        call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));

        $stmt->execute();
        if($stmt->error)
        {
            return false;
        }

        return $stmt->insert_id;
    }

    public function update($query, $type, $params)
    {
        $stmt = $this->prepare($query);

        array_unshift($params, $type);
        call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));

        $stmt->execute();
        if($stmt->error)
        {
            return false;
        }

        return $stmt->affected_rows ;
    }

    public function query($query)
    {
        $rs = $this->conn->query($query);
        if(!$rs)
        {
            return false;
        }
        return $rs;
    }

    public function getOne($query, $class = '')
    {
        $rs = $this->query($query);

        if(is_bool($rs))
        {
            return $rs;
        }

        if($class)
        {
            return $rs->fetch_object($class);
        } else
        {
            return $rs->fetch_assoc();
        }
    }

    public function quickSelect($query, $class = '', $key = '')
    {
        $rs = $this->query($query);

        $result = array();

        if($class)
        {
            while($obj = $rs->fetch_object($class))
            {
                if($key)
                {
                    #if(isset($result[$obj->$key])) continue;

                    $result[$obj->$key] = $obj;
                } else
                {
                    $result[] = $obj;
                }
            }
        } else
        {
            while($obj = $rs->fetch_assoc())
            {
                if($key)
                {
                    $result[$obj[$key]] = $obj;
                } else
                {
                    $result[] = $obj;
                }
            }
        }

        return $result;
    }

    public function quickSelectPlain($query, $key = '')
    {
        $rs = $this->query($query);
        $result = array();

        while($obj = $rs->fetch_assoc()) {
            $result[] = $obj[$key];
        }

        return $result;
    }


    static function getInstance()
    {
        if(isset(self::$instance))
        {
            return self::$instance;
        }

        $config = Config::get('main', 'db');

        self::$instance = new self();

        self::$instance->addServer($config);

        return self::$instance;
    }

    public function fetch_assoc(&$stmt)
    {
        $result = array();
        $meta = $stmt->result_metadata();

        $row = array();
        $params = array();

        while ($field = $meta->fetch_field())
        {
            $params[] = &$row[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $params);

        while ($stmt->fetch())
        {
            foreach($row as $key => $val)
            {
                $c[$key] = $val;
            }

            $result[] = $c;
        }

        return $result;
    }

    public function fetch_object(&$stmt, $class)
    {
        $result = array();
        $meta = $stmt->result_metadata();

        $row = array();
        $params = array();

        while ($field = $meta->fetch_field())
        {
            $params[] = &$row[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $params);

        while ($stmt->fetch())
        {
            $object = new $class();
            foreach($row as $key => $val)
            {
                $object->set($key, $val);
            }

            $result[] = $object;
        }

        return $result;
    }

    public function fetch_one_object(&$stmt, $class)
    {
        $meta = $stmt->result_metadata();

        $row = array();
        $params = array();

        while ($field = $meta->fetch_field())
        {
            $params[] = &$row[$field->name];
        }

        call_user_func_array(array($stmt, 'bind_result'), $params);

        $stmt->fetch();
        $object = new $class();
        foreach($row as $key => $val)
        {
            $object->set($key, $val);
        }

        return $object;
    }

    public function destroy()
    {
        $this->conn->close();
    }
}