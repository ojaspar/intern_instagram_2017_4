<?php
require_once 'bootstrap/init.php';
if(!Session::exists(Config::get('session/session_name'))){
    Redirect::to('login.php');
}
$errors = array();
if (Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validator = new Validator();
        $validator->validate($_POST, [
            'description' => 'required',
            'image' => 'image:1',
            'caption' => 'required|max:50'
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors() as $error) {
                array_push($errors, $error);
            }
        } else {
            $post = new Post();
            try {
                $post->create($_FILES['image'], array(
                    'user_id' => (int) Session::get(Config::get('session/session_name')),
                    'description' => sanitize(Input::get('description')),
                    'caption' => sanitize(Input::get('caption')),
                    'created_at' => date('Y-m-d H:i:s')
                ));
            } catch (Exception $e) {
                array_push($errors, $e->getMessage());
            }
        }
    } else {
        Redirect::to(404);
    }
}

$posts = (new Post())->all();
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Instagram</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="col-sm-8 col-sm-offset-2 h-padding-zero">
    <?php include 'includes/pages/header.php'; ?>
    <?php include 'includes/pages/notify.php'; ?>

    <?php
        if($posts) {
            foreach ($posts as $post) {
    ?>

        <div class="row panel h-margin-zero">
            <div class="col-sm-7 h-padding-zero">
                <div class="post-image-container">
                    <img src="<?php echo $post->media_path; ?>" class="post-image">
                </div>
            </div>
            <div class="col-sm-5">
                <div class="row post-user-container">
                    <div class="col-sm-4 col-xs-2 col-lg-3">
                        <img class="" width="80px" height="60px" src="assets/images/avatar.jpg">
                    </div>

                    <div class="col-xs-6 col-sm-4 col-lg-5">
                        <h5><strong><?php echo (new User($post->user_id))->data()->name ?></strong></h5>
                    </div>
                    <?php if ($post->user_id !== Session::get(Config::get('session/session_name'))) {
                        ?>
                    <div class="col-xs-4 col-sm-4 col-lg-4 text-right">
                        <?php $following = (new Follow())->following(Session::get(Config::get('session/session_name')), $post->user_id); ?>
                        <button class="btn follow_button_<?php echo $post->user_id; ?> btn-sm<?php echo (($following) ? ' btn-success' : ' btn-primary'); ?>"
                                style="margin-top: 1em;"
                                onclick="<?php echo (($following) ? 'unfollow('.$post->user_id .')' : 'follow('.$post->user_id .')'); ?>">
                            <?php echo (($following) ? 'Following' : 'Follow'); ?>
                        </button>
                    </div>
                    <?php } ?>

                </div>
                <hr style="margin:0px;">

                <div class="row post-body-container">
                    <div class="col-sm-12">
                        <h5><strong><?php echo $post->caption; ?></strong></h5>
                        <p><?php echo $post->description; ?></p>
                    </div>
                    <div class="col-sm-12">
                        <p><b><em>Comments</em></b></p>
                    </div>
                    <div id="comment_section_<?php echo $post->id; ?>">
                        <?php
                            if($comments = (new Comment())->getPostComments($post->id)) {
                                foreach ($comments as $comment) {
                                    ?>
                                    <div class="col-sm-12">
                                        <p><strong><?php echo (new User($comment->user_id))->data()->name; ?></strong></p>
                                        <p><?php echo $comment->comment; ?></p>
                                    </div>
                        <?php
                                }
                            }

                        ?>
                    </div>

                </div>
                <div class="row post-like-container">
                    <div class="col-sm-12">
                        <p>
                            <span>
                                <?php $liked = (new Like())->isLiked($post->id, Session::get(Config::get('session/session_name'))); ?>
                                <i id="like_button_<?php echo $post->id; ?>" class="glyphicon glyphicon-heart<?php echo (($liked) ? ' liked' : ' not-liked'); ?>"
                                   onclick="<?php echo (($liked) ? 'unlike('.$post->id .')' : 'like('.$post->id .')'); ?>"></i>
                                &nbsp;&nbsp;<strong><span id="like_count_<?php echo $post->id; ?>">
                                        <?php echo (new Like())->count($post->id); ?>
                                    </span></strong>
                            </span>
                            <span class="pull-right">
                                <button class="btn btn-primary btn-xs" onclick="triggerCommentModal(<?php echo $post->id ?>)">Comment</button>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

    <?php

        }} else {
            echo 'There are no posts at the moment';
        }
    ?>

    <?php include 'includes/pages/post_modal.php'; ?>

    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/index.js"></script>
</div>
</body>
</html>