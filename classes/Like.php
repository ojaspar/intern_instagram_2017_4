<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/19/17
 * Time: 2:01 PM
 */

class Like
{
    private $db = null;

    private $tableName = 'likes';

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

    public function delete($post_id, $user_id)
    {
        $sql = 'DELETE FROM '. $this->tableName .' WHERE post_id = ?  AND user_id = ?';
        if($this->db->query($sql, array($post_id, $user_id))->error()) {
            throw new Exception("An error occurred");
        }
    }

    public function isLiked($post_id, $user_id)
    {
        $sql = 'SELECT id FROM '. $this->tableName .' WHERE post_id = ?  AND user_id = ?';
        if($this->db->query($sql, array($post_id, $user_id))->count()) {
            return true;
        }
        return false;
    }

    public function count($post_id)
    {
        return $this->db->get($this->tableName, array('post_id', '=', $post_id))->count();
    }
}