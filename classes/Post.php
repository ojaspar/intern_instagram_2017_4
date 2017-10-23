<?php
/**
 * Created by PhpStorm.
 * User: melas
 * Date: 10/19/17
 * Time: 2:01 PM
 */

class Post
{
    private $db = null;
    private $tableName = 'posts';
    private $target_dir = "uploads/";
    private $imagePath;

    /**
     * Post constructor.
     * @param null $db
     */
    public function __construct()
    {
        $this->db = DB::getInstance();
    }

    public function all()
    {
        return $this->db->get($this->tableName, [], ['created_at', 'DESC'])->results();
    }

    public function create($file, array $attributes)
    {

        if(count($attributes) < 1) {
            throw new Exception('You\'ve not passed any attributes');
        }
        if(!$this->uploadImage($file)) {
            throw new Exception('Image could not be uploaded');
        }

        $attributes['media_path'] = $this->imagePath;
        if($this->db->create($this->tableName, $attributes)->error()) {
            throw new Exception('An error occured while trying to create this post');
        }
    }

    public function uploadImage($file)
    {

        $this->imagePath = $this->target_dir . Hash::unique() . '_' . time()
            . '.' . pathinfo(basename($file["name"]),PATHINFO_EXTENSION);
        if (move_uploaded_file($file["tmp_name"], $this->imagePath)) {
            return true;
        }

        return false;
    }


}