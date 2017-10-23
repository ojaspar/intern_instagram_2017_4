<?php
require_once 'bootstrap/init.php';
$response = [];
if (Input::exists()) {
    $post_id = Input::get('post_id');
    try {
        (new Like())->create([
            'post_id' => $post_id,
            'user_id' => Session::get(Config::get('session/session_name')),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        $response['status'] = 1;
    } catch (Exception $e) {
        $response['status'] = 0;
    }
} else {
    $response['status'] = 0;
}
echo json_encode($response);