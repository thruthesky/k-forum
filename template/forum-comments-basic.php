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


<?php
function comments_basic($comment, $args, $depth) {
$parent_comment = null;
if ( $comment->comment_parent ) $parent_comment = get_comment($comment->comment_parent);
?>
<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>" comment-id="<?php comment_ID() ?>">
    <div class="comment-body">
        <div class="comment-meta">
            <?php if ( $parent_comment ) : ?>
                <?php echo get_comment_author(); ?> commented on <?php echo get_comment_author($parent_comment)?> at
            <?php else : ?>
                <?php echo get_comment_author(); ?> wrote at
            <?php endif; ?>
            <?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?>
            <div>
                <?php _e('No.:', 'k-forum')?> <?php echo $comment->comment_ID?>
            </div>
        </div>


        <?php
        $attachments = forum()->markupCommentAttachments( FORUM_COMMENT_POST_NUMBER + $comment->comment_ID );
        ?>


        <div class="photos"><?php echo $attachments['images']?></div>
        <div class="files"><?php echo $attachments['attachments']?></div>


        <div class="comment-text">
        <?php comment_text(); ?>
        </div>

        <div class="comment-buttons">
            <div class="reply">Reply</div>
            <div class="edit">
                <a href="<?php echo forum()->commentEditURL( $comment->comment_ID )?>">Edit</a>
            </div>
            <div class="delete">
                <a href="<?php echo forum()->commentDeleteURL( $comment->comment_ID )?>">Delete</a>
            </div>
            <div class="report" title="This function is not working, yet.">Report</div>
            <div class="like" title="This function is not working, yet.">Like</div>
        </div>
    </div>
    <hr>
    <?php
    } /** EO comments_basic callback */
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
                            <label for="post-submit-button"><input id="post-submit-button" type="submit" value="<?php _e('Comment Submit', 'k-forum')?>"></label>
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
            window.addEventListener( 'load', function() {
                jQuery( function( $ ) {
                    var t = _.template($('#comment-form-template').html());
                    $('.reply-placeholder').html(t({ parent : 0 }));
                });
            });
        </script>

        <?php if ( have_comments() ) : ?>

            <div class="comments-title"><?php printf('No. of Comments: %d', get_comments_number()); ?></div>

            <?php /** @todo Version compatibility */if ( function_exists('the_comments_navigation') ) the_comments_navigation(); ?>

            <ol class="comment-list">
                <?php
                wp_list_comments( array(
                    'avatar_size' => 42,
                    'callback' => 'comments_basic'
                ) );
                ?>
            </ol><!-- .comment-list -->

            <?php /** @todo Version compatibility */if ( function_exists('the_comments_navigation') ) the_comments_navigation(); ?>

        <?php endif; // Check for have_comments(). ?>

    </div><!-- .comments-area -->
