<?php
get_header();
if ( is_numeric(seg(1) ) ) {
    $comment_ID = seg(1);
    $comment = get_comment( $comment_ID );
}
else {
    wp_die("Error: wrong comment number");
}
?>
    <h2>Comment EDIT</h2>


    <script>
        var url_endpoint = "<?php echo home_url("forum/submit")?>";
        var max_upload_size = <?php echo wp_max_upload_size();?>;
    </script>


    <section id="post-new">
        <form action="<?php echo home_url("forum/submit")?>" method="post" enctype="multipart/form-data">

            <input type="hidden" name="do" value="comment_create">
            <?php if ( $comment ) : ?>
                <input type="hidden" name="id" value="<?php echo $comment->comment_ID?>">
            <?php endif; ?>
            <input type="hidden" name="file_ids" value="">
            <label for="title">Title</label>

            <label for="content">Content</label>
            <div class="text">
                <textarea name="comment_content"><?php echo $comment->comment_content?></textarea>
            </div>

            <?php
            //$attachments = forum()->markupAttachments( get_the_ID() );
            ?>
<?php
/*
            <div class="photos"><?php echo $attachments['images']?></div>
            <div class="files"><?php echo $attachments['attachments']?></div>

            <div class="file-upload">
                <span class="dashicons dashicons-camera"></span>
                <span class="text"><?php _e('Choose File', 'k-forum')?></span>
                <input type="file" name="file" onchange="forum.on_change_file_upload(this);" style="opacity: .001;">
            </div>
            <div class="loader">
                <img src="<?php echo FORUM_URL ?>/img/loader14.gif">
                File upload is in progress. Please wait.
            </div>
*/?>
            <label for="post-submit-button"><input id="post-submit-button" type="submit"></label>
            <label for="post-cancel-button"><div id="post-cancel-button">Cancel</div></label>

        </form>
    </section>





<?php
get_footer();
?>