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
?>

<style>
    .buttons {
        overflow: auto;
    }
    .buttons > div {
        float: left;
        color: #3b3a36;
        padding: .1em .6em .1em 0;
    }
</style>
<?php

function comments_basic($comment, $args, $depth) {

    $tag       = 'li';

    ?>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">

    <div id="div-comment-<?php comment_ID() ?>" class="comment-body">

        <div class="comment-author vcard">
            <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php echo get_comment_author(); ?>
        </div>

        <div class="comment-meta commentmetadata"><?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></div>

        <?php comment_text(); ?>

        <div class="buttons">
            <div class="edit">
                Edit
            </div>
            <div class="delete">
                Delete
            </div>
            <div class="report">
                Report
            </div>
            <div class="like">
                Like
            </div>
            <div class="reply">
                Reply
            </div>
        </div>

    </div>

    <?php
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            _e("No. of Comments: ", 'k-fourm');
            echo $comments_number;
            ?>
        </h2>


        <style scoped>
            .reply {
                overflow: auto;
            }
            .reply form {

            }
            .reply .line {

            }
            .reply .line.comment-content {

            }
            .reply .line.comment-content textarea {
                margin: 0;
                height: 4em;
                width: 100%;
            }
            .reply .buttons input {
                height: 2em;
                float: right;
            }
            .reply .buttons .fa-camera {
                font-size: 1.8em;
            }

        </style>
        <div class="reply">
            <form action="<?php echo home_url("forum/submit")?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="do" value="comment_create">
                <input type="hidden" name="post_ID" value="<?php the_ID()?>">
                <div class="line comment-content">
                    <label for="comment-content" style="display:none;">
                        <?php _e('Comment Content', 'k-fourm')?>
                    </label>
                    <textarea id="comment-content" name="comment_content"></textarea>
                </div>
                <div class="line buttons">
                    <i class="fa fa-camera"></i> Choose File
                    <input type="submit">
                </div>
            </form>
        </div>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'short_ping'  => true,
                'avatar_size' => 42,
                'callback' => 'comments_basic'
            ) );
            ?>
        </ol><!-- .comment-list -->

        <?php the_comments_navigation(); ?>

    <?php endif; // Check for have_comments(). ?>

</div><!-- .comments-area -->
