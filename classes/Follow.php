<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/23/17
 * Time: 11:22 AM
 */

class Follow
{
    private $db = null;

    private $tableName = 'follows';

    /**
     * Follow constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }


    public function create($attributes)
    {
        if($this->db->create($this->tableName, $attributes)->error()) {
            throw new Exception("An error occurred");
        }
    }

    public function delete($follower, $followed)
    {
        $sql = 'DELETE FROM '. $this->tableName .' WHERE follower = ?  AND followed = ?';
        if($this->db->query($sql, array($follower, $followed))->error()) {
            throw new Exception("An error occurred");
        }
    }

    public function following($follower, $followed)
    {
        $sql = 'SELECT id FROM '. $this->tableName .' WHERE follower = ?  AND followed = ?';
        if($this->db->query($sql, array($follower, $followed))->count()) {
            return true;
        }
        return false;
    }

}