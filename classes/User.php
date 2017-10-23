<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/17/17
 * Time: 5:01 PM
 */

class User
{
    private $db = null;
    private $tableName = 'users';
    private $data = null;
    private $sessionName;

    /**
     * User constructor.
     * @param null $db
     */
    public function __construct($user_id = null)
    {
        $this->db = DB::getInstance();
        $this->sessionName = Config::get('session/session_name');

        if ($user_id) {
            $this->findById($user_id);
        }
    }


    public function create($attributes)
    {
        if(count($attributes) < 1) {
            return false;
        }
        if(!$this->db->create($this->tableName, $attributes)->error()) {
            return true;
        }
        return false;
    }

    public function findById($id)
    {
        $result = $this->db->get($this->tableName, array('id', '=', $id));
        if($result->count()) {
            $this->data = $result->first();
            return true;
        }
        return false;
    }

    public function find($username)
    {
        $result = $this->db->get($this->tableName, array('username', '=', $username));
        if($result->count()) {
            $this->data = $result->first();
            return true;
        }
        return false;
    }

    public function login($username = null, $password = null)
    {
        if ($username) {
            if($this->find($username)) {
                if($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->sessionName, $this->data()->id);
                    return true;
                }
            } else {
                return false;
            }
        }
        return false;
    }

    public function logout()
    {
        Session::delete($this->sessionName);
    }

    public function data()
    {
        return $this->data;
    }
}