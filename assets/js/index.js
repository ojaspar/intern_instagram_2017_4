function triggerCommentModal(post_id) {
    $("#post_id").val(post_id);
    $("#commentModal").modal();
}

function postComment() {
    var post_id = $('#post_id').val();
    var user_id = $('#user_id').val();
    var comment = $('#comment').val();
    var token = $('#token').val();
    $("#loader").show();
    $("#comment_post").css('disabled', true);
    $.ajax({
        url: 'comment.php',
        method: 'post',
        dataType: 'json',
        data: {
            comment: comment,
            user_id: user_id,
            post_id: post_id,
            token: token
        },
        success: function (data) {
            if(data.status === 1) {
                var html = '<div class="col-sm-12"><p><strong>'+ data.name +
                    '</strong></p><p>' + data.comment + '</p></div>';
                $("#comment_section_" + post_id).prepend(html);
            } else {
                alert(data.message);
            }
            $("#comment").val('');
            $("#loader").hide();
            $("#comment_post").css('disabled', false);
            $("#commentModal").modal('hide');
        },
        error: function() {
            $("#loader").hide();
            $("#comment_post").css('disabled', false);
            $("#commentModal").modal('hide');
            alert('An error occured. Please try again');
        }
    })
}

function like(post_id) {
    $.ajax({
       url: './like.php',
       dataType: 'json',
       method: 'POST',
       data: {post_id: post_id},
       success: function(data) {
           console.log('data');
           if (data.status === 1) {
               //switch buttons
               $("#like_button_" + post_id)
                   .removeClass('not-liked')
                   .addClass('liked')
                   .attr('onclick', 'unlike('+ post_id +')');
               var countElement = $("#like_count_" + post_id);
               var count = parseInt(countElement.text());
               countElement.text(++count);
           } else {
               alert('Like could not be applied');
           }
       },
       error: function() {
           alert('An error occured. Please try again');
       }
    });
}

function unlike(post_id) {
    $.ajax({
        url: './unlike.php',
        dataType: 'json',
        method: 'POST',
        data: {post_id: post_id},
        success: function(data) {
            if (data.status === 1) {
                $("#like_button_" + post_id)
                    .removeClass('liked')
                    .addClass('not-liked')
                    .attr('onclick', 'like('+ post_id +')');
                var countElement = $("#like_count_" + post_id);
                var count = parseInt(countElement.text());
                countElement.text(--count);

            } else {
                alert('An error occured. Please try again');
            }
        },
        error: function() {
            alert('An error occured. Please try again');
        }
    });
}

function follow(user_id) {
    $.ajax({
        url: './follow.php',
        dataType: 'json',
        method: 'POST',
        data: {user_id: user_id},
        success: function(data) {
            if (data.status === 1) {
                $(".follow_button_" + user_id)
                    .removeClass('btn-primary')
                    .addClass('btn-success')
                    .text('Followed')
                    .attr('onclick', 'unfollow('+ user_id +')');
            } else {
                alert('An error occured. Please try again');
            }
        },
        error: function() {
            alert('An error occured. Please try again');
        }
    });
}

function unfollow(user_id) {
    $.ajax({
        url: './unfollow.php',
        dataType: 'json',
        method: 'POST',
        data: {user_id: user_id},
        success: function(data) {
            if (data.status === 1) {
                $(".follow_button_" + user_id)
                    .removeClass('btn-success')
                    .addClass('btn-primary')
                    .text('Follow')
                    .attr('onclick', 'follow('+ user_id +')');
            } else {
                alert('An error occured. Please try again');
            }
        },
        error: function() {
            alert('An error occured. Please try again');
        }
    });
}