<?php
wp_enqueue_style( 'font-awesome', FORUM_URL . 'css/font-awesome/css/font-awesome.min.css' );
wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css' );
wp_enqueue_script( 'tether', FORUM_URL . 'js/tether.min.js' );
wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js' );

$cat = get_category_by_slug(FORUM_CATEGORY_SLUG);
$categories = lib()->get_categories_with_depth( $cat->term_id );
if ( isset($_REQUEST['category_id']) ) {
    $category = get_category( $_REQUEST['category_id'] );
}
else {
    $category = null;
}
?>
<style>
    <?php if ( isset($_REQUEST['category_id']) ) : ?>
    .forum-list {
        display: none;
    }
    <?php else : ?>
    .forum-create {
        display: none;
    }
    <?php endif; ?>
    .forum-create {
        margin-bottom: 2em;
    }
</style>
<script>

    jQuery(function($) {
        $('.forum-create-button').click(function(){
            $('.forum-create').show();
            $('.forum-list').hide();
        });
        $('.forum-create-cancel-button').click(function(){
            $('.forum-create').hide();
            $('.forum-list').show();
        });
    });
</script>

<div class="wrap k-forum">

    <h2><?php _e('K-Forum', 'k-forum')?></h2>

    <div>
        <?php _e('This is K forum.', 'k-forum')?>
    </div>

    <div class="forum-create">
        <h2><?php _e('Create a Forum', 'k-forum')?></h2>

        <form action="<?php echo forum()->doURL('forum_create')?>" method="post">
            <?php if ( $category ) { ?>
                <input type="hidden" name="category_id"  value="<?php if ( $category ) echo $category->term_id ?>">
            <?php } ?>
            <fieldset class="form-group">
                <label for="ForumID">
                    <?php _e('Forum ID', 'k-forum')?>
                </label>
                <input id='ForumID' class='form-control' type="text" name="id" placeholder="<?php _e('Please input forum ID', 'k-forum')?>" value="<?php if ( $category ) echo $category->slug ?>">
                <small class="text-muted"><?php _e('Input forum ID in lowercase letters, numbers and hypens.', 'k-forum')?></small>
            </fieldset>


            <fieldset class="form-group">
                <label for="ForumName">
                    <?php _e('Forum name', 'k-forum')?>
                </label>
                <input id='ForumName' class='form-control' type="text" name="name" placeholder="<?php _e('Please input forum name', 'k-forum')?>">
                <small class="text-muted"><?php _e('Input forum name. It should be less than four words.', 'k-forum')?></small>
            </fieldset>

            <fieldset class="form-group">
                <label for="ForumDesc"><?php _e('Forum description', 'k-forum')?></label>
                <textarea name="desc" class="form-control" id="ForumDesc" rows="3"></textarea>
                <small class="text-muted"><?php _e('Input forum description. It should be less than 100 words.', 'k-forum')?></small>
            </fieldset>


            <fieldset class="form-group">
                <label for="ForumParent"><?php _e('Parent Forum', 'k-forum')?></label>
                <select name="parent" class="form-control" id="ForumParent">
                    <option value=""><?php _e('Select Parent Forum', 'k-forum')?></option>
                    <?php
                    foreach ( $categories as $category ) {
                        $pads = str_repeat( '&nbsp;&nbsp;', $category->depth );
                        echo "<option value='{$category->term_id}'>$pads{$category->name}</option>";
                    }
                    ?>
                </select>
                <small class="text-muted"><?php _e('You can group or categorize forum by selecting Parent Forum', 'k-forum')?></small>
            </fieldset>


            <br>

            <input type="submit" class="btn btn-primary" value="SUBMIT FORUM">

            <button type="button" class="btn btn-secondary forum-create-cancel-button">Cancel</button>

        </form>
    </div>

    <div class="forum-list">

        <button class="button btn-primary forum-create-button"><?php _e('Create Forum', 'k-forum')?></button>


        <h2><?php _e('Forum List', 'k-forum')?></h2>

        <div class="forum-list container">
            <div class="row">
                <div class="col-xs-6 col-sm-3">Category</div>
                <div class="col-xs-3 col-sm-1">Edit</div>
                <div class="col-xs-3 col-sm-1">Posts</div>
                <div class="col-xs-12 col-sm-7">Description</div>
            </div>
            <?php
            foreach($categories as $category) {
                ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-3">
                        <a href="<?php echo forum()->listURL($category->slug)?>" target="_blank"><?php echo $category->name?></a>
                    </div>
                    <div class="col-xs-3 col-sm-1"><a href="<?php echo forum()->adminURL()?>&category_id=<?php echo $category->term_id?>">Edit</a></div>
                    <div class="col-xs-3 col-sm-1"><?php echo $category->count?></div>
                    <div class="col-xs-12 col-sm-7"><?php echo $category->description?></div>
                </div>
            <?php } ?>

        </div>
    </div>
</div>
