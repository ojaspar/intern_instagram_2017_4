<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/19/17
 * Time: 2:01 PM
 */

class Comment
{

    private $db = null;

    private $tableName = 'comments';
    /**
     * Comment constructor.
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function create(array $attributes)
    {
        if ($this->db->create($this->tableName, $attributes)->error()) {
            throw new Exception('An error occurred. Please try again');
        }
    }

    public function getPostComments($post_id)
    {
        return $this->db
            ->get($this->tableName, array('post_id', '=', $post_id), array('created_at', 'DESC'))
            ->results();
    }
}