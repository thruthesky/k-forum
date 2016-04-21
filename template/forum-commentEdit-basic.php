<?php
get_header();
wp_enqueue_style( 'forum-commentEdit-basic', FORUM_URL . 'css/forum-commentEdit-basic.css' );
if ( is_numeric(seg(1) ) ) {
    $comment_ID = seg(1);
    $comment = get_comment( $comment_ID );
}
else {
    wp_die("Error: wrong comment number");
}
?>
    <h2><?php _e('Comment Edit', 'k-forum')?></h2>

    <script>
        var url_endpoint = "<?php echo home_url("forum/submit")?>";
        var max_upload_size = <?php echo wp_max_upload_size();?>;
    </script>


    <section class="comment-new comment-edit">
        <form action="<?php echo home_url("forum/submit")?>" method="post" enctype="multipart/form-data">

            <input type="hidden" name="do" value="comment_create">
            <?php if ( $comment ) : ?>
                <input type="hidden" name="comment_ID" value="<?php echo $comment->comment_ID?>">
            <?php endif; ?>
            <input type="hidden" name="file_ids" value="">


            <div class="text">
                <label for="comment_content" style="display:none;">Content</label>
                <textarea id="comment_content" name="comment_content"><?php echo $comment->comment_content?></textarea>
            </div>


            <?php
            $attachments = forum()->markupEditAttachments( FORUM_COMMENT_POST_NUMBER + $comment->comment_ID );
            ?>

            <div class="photos"><?php echo $attachments['images']?></div>
            <div class="files"><?php echo $attachments['attachments']?></div>

            <div class="buttons">
                <div class="file-upload">
                    <i class="fa fa-camera"></i>
                    <span class="text"><?php _e('Choose File', 'k-forum')?></span>
                    <input type="file" name="file" onchange="forum.on_change_file_upload(this);" style="opacity: .001;">
                </div>
                <div class="right">
                    <label for="post-submit-button"><input id="post-submit-button" class="btn btn-primary btn-sm" type="submit" value="<?php _e('POST SUBMIT', 'k-forum')?>"></label>
                    <label for="post-cancel-button"><a href="javascript:history.go(-1);" id="post-cancel-button" class="btn btn-secondary-outline btn-sm"><?php _e('Cancel', 'k-forum')?></a></label>
                </div>
            </div>

            <div class="loader">
                <img src="<?php echo FORUM_URL ?>/img/loader14.gif">
                File upload is in progress. Please wait.
            </div>


        </form>
    </section>





<?php
get_footer();
?>