<?php
require_once 'bootstrap/init.php';
if (Input::exists()) {
    $validator = new Validator();
    $validator->validate($_POST, [
       'comment' => 'required'
    ]);

    if($validator->fails()) {
        $response = [];
        $response['status'] = 0;
        $response['message'] = $validator->errors()[0];
        echo json_encode($response); exit();
    }

    $comment = new Comment();
    try {
        $comment->create([
            'post_id' => sanitize(Input::get('post_id')),
            'user_id' => sanitize(Input::get('user_id')),
            'comment' => sanitize(Input::get('comment')),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $response = [];
        $response['status'] = 1;
        $response['message'] = ['Comment created successfully'];
        $response['comment'] = sanitize(Input::get('comment'));
        $response['name'] =
            (new User((int) sanitize(Input::get('user_id'))))->data()->name;
        echo json_encode($response); exit();

    } catch (Exception $e) {
        $response = [];
        $response['status'] = 0;
        $response['message'] = $e->getMessage();
        echo json_encode($response); exit();

    }
}