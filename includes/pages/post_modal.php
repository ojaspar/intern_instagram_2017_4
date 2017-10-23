<!-- Post Modal-->
<div id="postModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Post Picture</h4>
      </div>
      <div class="modal-body">
        <form method="post" id="new_post" enctype="multipart/form-data">
            <div class="form-group">
                <label id="caption">Caption</label>
                <input type="text" class="form-control" id="caption" placeholder="Enter a short caption for your post" name="caption" value="<?php echo sanitize(Input::get('caption')); ?>">
            </div>

            <div class="form-group">
                <label id="description">Description</label>
                <textarea class="form-control" id="description" rows="3" placeholder="Enter a short description for your post" name="description">
                    <?php echo sanitize(Input::get('description')); ?>
                </textarea>
            </div>

            <div class="input-group">
                <input class="form-control" type="file" name="image" accept="image/jpeg">
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-xs" form="new_post">Post</button>
      </div>
    </div>

  </div>
</div>
<!-- end of postModal-->


<!-- Comment Modal-->
<div id="commentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Post Comment &nbsp;
                    <i class="fa fa-spinner fa-spin" id="loader" style="display: none"></i>
                </h4>
            </div>
            <div class="modal-body">
                <form method="post" id="new_comment">
                    <input type="hidden" id="user_id" value="<?php echo Session::get(Config::get('session/session_name')) ?>">
                    <input type="hidden" id="token" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="hidden" id="post_id" value="">
                    <div class="form-group">
                        <label id="description">Comment</label>
                        <textarea class="form-control" id="comment" rows="2" placeholder="Enter your comment"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-xs" data-dismiss="modal">Close</button>
                <button class="btn btn-primary btn-xs" onclick="postComment();" id="comment_post">Post</button>
            </div>
        </div>

    </div>
</div>
<!-- end of postModal-->