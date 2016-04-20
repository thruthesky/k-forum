<?php
get_header();
wp_enqueue_style( 'basic', FORUM_URL . 'css/forum-edit-basic.css' );
if ( is_numeric(seg(1) ) ) {
    $post = get_post(seg(1));
    $category = get_the_category( $post->ID );
    $category_id = current( $category )->term_id;
}
else {
    $post = null;
    $category = get_category_by_slug( segment(1) );
    $category_id = $category->term_id;
}
?>



    <script>
        var url_endpoint = "<?php echo home_url("forum/submit")?>";
        var max_upload_size = <?php echo wp_max_upload_size();?>;
    </script>


    <section id="post-new">

        <div class="post-edit-meta">
            <div class="top">
                <h1 class="forum-title"><?php echo $category->name?></h1>
                <div class="forum-description"><?php echo $category->description?></div>
            </div>
        </div>


        <form action="<?php echo home_url("forum/submit")?>" method="post" enctype="multipart/form-data">

            <input type="hidden" name="do" value="post_create">
            <?php if ( $post ) : ?>
                <input type="hidden" name="id" value="<?php echo $post->ID?>">
            <?php endif; ?>
            <input type="hidden" name="category_id" value="<?php echo $category_id?>">
            <input type="hidden" name="file_ids" value="">
            <label for="title" style="display: none;">Title</label>
            <div class="text">
                <input type="text" id="title" name="title" value="<?php echo $post ? esc_attr($post->post_title) : ''?>" placeholder="<?php _e('Please input title', 'k-forum')?>">
            </div>

            <label for="content" style="display: none;">Content</label>
            <div class="text">
                <?php
                if ( $post ) {
                    $content = $post->post_content;
                }
                else {
                    $content = '';
                }
                $editor_id = 'new-content';
                $settings = array(
                    'textarea_name' => 'content',
                    'media_buttons' => false,
                    'textarea_rows' => 20,
                    'quicktags' => false
                );
                wp_editor( $content, $editor_id, $settings );

                ?>

            </div>

            <?php
            $attachments = forum()->markupEditAttachments( get_the_ID() );
            ?>

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

            <label for="post-submit-button"><input id="post-submit-button" type="submit"></label>
            <label for="post-cancel-button"><div id="post-cancel-button">Cancel</div></label>

        </form>
    </section>





<?php
get_footer();
?>