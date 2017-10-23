<?php
require_once 'bootstrap/init.php';
$response = [];
if (Input::exists()) {
    $user_id = Input::get('user_id');
    try {
        (new Follow())->create([
            'followed' => $user_id,
            'follower' => Session::get(Config::get('session/session_name')),
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