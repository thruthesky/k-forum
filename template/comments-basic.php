<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 *
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}
wp_enqueue_style( 'forum-comments-basic', FORUM_URL . 'css/forum-comments-basic.css' );
?>
<!--suppress ALL -->
<script>
    var url_endpoint = "<?php echo home_url("forum/submit")?>";
    var max_upload_size = <?php echo wp_max_upload_size();?>;
</script>


<script>
    jQuery( function( $ ) {
        $('.comment-list .reply').click(function(){

            var $this = $(this);
            var $comment_form = $('.comment-new');
            var $comment_form_template = $('#comment-form-template');
            $comment_form.remove();

            var $buttons = $this.parent();
            var $comment_body = $buttons.parent();
            var $comment = $comment_body.parent();
            var comment_id = $comment.attr('comment-id');
            console.log(comment_id);
            var t = _.template( $comment_form_template.html() );
            $comment_body.append(t({ parent : comment_id }));
        });
    });
</script>
<?php
function comments_basic($comment, $args, $depth) {
?>
<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>" comment-id="<?php comment_ID() ?>">
    <div class="comment-body">
        <div class="comment-author vcard">
            <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php echo get_comment_author(); ?>
        </div>

        <div class="comment-meta commentmetadata">
            <?php _e('No.:', 'k-forum')?> <?php echo $comment->comment_ID?>
            <?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?>
        </div>


        <?php
        $attachments = forum()->markupCommentAttachments( FORUM_COMMENT_POST_NUMBER + $comment->comment_ID );
        ?>

        <div class="photos"><?php echo $attachments['images']?></div>
        <div class="files"><?php echo $attachments['attachments']?></div>


        @익명 님에게 ...
        <?php comment_text(); ?>
        <div class="comment-buttons">
            <div class="reply">Reply</div>
            <div class="edit">
                <a href="<?php echo forum()->commentEditURL( $comment->comment_ID )?>">Edit</a>
            </div>
            <div class="delete">
                <a href="<?php echo forum()->commentDeleteURL( $comment->comment_ID )?>">Delete</a>
            </div>
            <div class="report">Report</div>
            <div class="like">Like</div>
        </div>
    </div>
    <?php
    }
    ?>

    <div id="comments" class="comments-area">





        <script type="text/template" id="comment-form-template">
            <section class="reply comment-new">
                <form action="<?php echo home_url("forum/submit")?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="do" value="comment_create">
                    <input type="hidden" name="comment_post_ID" value="<?php the_ID()?>">
                    <input type="hidden" name="comment_parent" value="<%=parent%>">
                    <input type="hidden" name="file_ids" value="">
                    <div class="line comment-content">
                        <label for="comment-content" style="display:none;">
                            <?php _e('Comment Content', 'k-fourm')?>
                        </label>
                        <textarea id="comment-content" name="comment_content" placeholder="<?php _e('Please input comment', 'k-forum')?>"></textarea>
                    </div>
                    <div class="photos"></div>
                    <div class="files"></div>
                    <div class="line buttons">
                        <div class="file-upload">
                            <i class="fa fa-camera"></i>
                            <span class="text"><?php _e('Choose File', 'k-forum')?></span>
                            <input type="file" name="file" onchange="forum.on_change_file_upload(this);" style="opacity: .001;">
                        </div>
                        <div class="submit">
                            <label for="post-submit-button"><input id="post-submit-button" type="submit"></label>
                        </div>
                    </div>
                    <div class="loader">
                        <img src="<?php echo FORUM_URL ?>/img/loader14.gif">
                        <?php _e('File upload is in progress. Please wait.', 'k-forum')?>
                    </div>
                </form>
            </section>
        </script>
        <div class="reply-placeholder"></div>
        <script>
            jQuery( function( $ ) {
                var t = _.template($('#comment-form-template').html());
                $('.reply-placeholder').html(t({ parent : 0 }));
            });
        </script>



        <?php if ( have_comments() ) : ?>

            <h2 class="comments-title">
                <?php
                $comments_number = get_comments_number();
                _e("No. of Comments: ", 'k-fourm');
                echo $comments_number;
                ?>
            </h2>

            <?php the_comments_navigation(); ?>

            <ol class="comment-list">
                <?php
                wp_list_comments( array(
                    'avatar_size' => 42,
                    'callback' => 'comments_basic'
                ) );
                ?>
            </ol><!-- .comment-list -->

            <?php the_comments_navigation(); ?>

        <?php endif; // Check for have_comments(). ?>

    </div><!-- .comments-area -->
