<?php

class DB 
{
	private static $instance = null;
	private $pdo, $query, $results, $error = false, $count = 0;

	public function __construct()
	{
		try {
			$this->pdo = new PDO('mysql:host='. Config::get('mysql/host') . ';dbname=' .Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));

		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() 
	{
		if (!isset(self::$instance)) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	public function query($query, $params = array())
	{
	    $this->error = false;
		$this->query = $this->pdo->prepare($query);

		if ($this->query)
		{
			if (count($params)) {
                $x = 1;
			    foreach ($params as $param) {
					$this->query->bindValue($x, $param);
					$x++;
				}	
			}
			
			if ($this->query->execute()) {
				$this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
				$this->count = $this->query->rowCount();
			} else {
				$this->error = true;
			}
		} else {
			$this->error = true;
		}

		return $this;
	}

	public function action($table, $action)
    {

    }

	public function get($table, $params = array(), $filters = array())
    {
        $condition = [];
        $sql = "SELECT * FROM {$table}";

        if (count($params) && count($params) === 3) {
            $field = $params[0];
            $operator = $params[1];
            $condition[0] = $params[2];
            $sql .= " WHERE {$field} {$operator} ?";
        }

        if (count($filters) && count($filters) === 2) {
            $field = $filters[0];
            $mode = $filters[1];
            $sql .= " ORDER BY {$field} {$mode}";
        }

        $this->query($sql, $condition);

        return $this;
    }

    public function delete($table, $params = array()) {
        $condition = [];
        $statement = '';
        if (count($params) && count($params) === 3) {
            $field = $params[0];
            $operator = $params[1];
            $condition[0] = $params[2];
            $statement = " WHERE {$field} {$operator} ?";
        } else {
            $this->error = true;
            return $this;
        }

        $sql = "DELETE FROM {$table}{$statement}";
        $this->query($sql, $condition);

        return $this;
    }

    public function create($table, array $params)
    {
        if (count($params) > 0) {
            $keys = array_keys($params);
            $binders = '';
            $x = 1;
            foreach ($params as $param) {
                $binders .= '?';
                if ($x < count($params)) {
                    $binders .=', ';
                }
                $x++;
            }
        } else {
            $this->error = true;
            return $this;
        }

        $sql = "INSERT INTO {$table} (". implode(', ', $keys).") VALUES ({$binders})";

        if(!$this->query($sql, $params)->error()) {
            $this->error = false;
            return $this;
        } else {
            $this->error = true;
            return $this;
        }
    }

    public function update($table, $id, array $params)
    {
        if (count($params) > 0) {
            $keys = array_keys($params);
            $fields = '';
            $x = 1;
            foreach ($keys as $key) {
                $fields .= $key . '= ?';
                if ($x < count($keys)) {
                    $fields .=', ';
                }
                $x++;
            }
        } else {
            $this->error = true;
            return $this;
        }
        $sql = "UPDATE {$table} SET {$fields} WHERE id = {$id}";
        if(!$this->query($sql, $params)->error()) {
            $this->error = true;
            return $this;
        } else {
            $this->error = false;
            return $this;
        }
    }

    public function error()
	{
		return $this->error;
	}

	public function results()
    {
        return $this->results;
    }

    public function count()
    {
        return $this->count;
    }

    public function first()
    {
        return $this->results()[0];
    }
}