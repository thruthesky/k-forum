<?php
wp_enqueue_style( 'font-awesome', FORUM_URL . 'css/font-awesome/css/font-awesome.min.css' );
wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css' );
wp_enqueue_script( 'tether', FORUM_URL . 'js/tether.min.js' );
wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js' );




// @todo Bug : somehow routes is being removed. It re-registers here.
// Like when the theme changes
// Like when forum data already exists before installing this plugin. so, it does not do 'doDefault'.
forum()->addRoutes();

$cat = forum()->getForumCategory();
$categories = [];
if ( $cat ) {
    $categories = lib()->get_categories_with_depth( $cat->term_id );
}

if ( isset($_REQUEST['category_id']) ) { // editing
    $category = get_category( $_REQUEST['category_id'] );
    $category_id = $category->term_id;
    $parent_category = get_category($category->parent);
}
else {
    $category = null;
    $parent_category = get_category_by_slug(FORUM_CATEGORY_SLUG);
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
        <?php if ( $category ) : ?>
        $('.forum-create [name="parent"]').val("<?php echo $category->parent?>");
        <?php endif; ?>
    });
</script>

<div class="wrap k-forum">

    <h2><?php _e('K-Forum', 'k-forum')?></h2>

    <div>
        <?php _e('K-Forum Description', 'k-forum')?>
    </div>

    <div class="forum-create">
        <h2>
            <?php if ( $category ) : ?>
                <?php _e('Update a Forum', 'k-forum')?>
            <?php else : ?>
                <?php _e('Create a Forum', 'k-forum')?>
            <?php endif; ?>
        </h2>



        <form action="<?php echo forum()->doURL('forum_create')?>" method="post">
            <?php if ( $category ) { ?>
                <input type="hidden" name="category_id"  value="<?php if ( $category ) echo $category->term_id ?>">
            <?php } ?>
            <fieldset class="form-group">
                <label for="ForumID">
                    <?php _e('Forum ID', 'k-forum')?>
                </label>
                <input id='ForumID' class='form-control' type="text" name="id" placeholder="<?php _e('Please input forum ID', 'k-forum')?>" value="<?php if ( $category ) echo $category->slug ?>">
                <small class="text-muted"><?php _e('Input forum ID in lowercase letters, numbers and hypens. It is a slug.', 'k-forum')?></small>
            </fieldset>


            <fieldset class="form-group">
                <label for="ForumName">
                    <?php _e('Forum name', 'k-forum')?>
                </label>
                <input id='ForumName' class='form-control' type="text" name="name" placeholder="<?php _e('Please input forum name', 'k-forum')?>" value="<?php if ( $category ) echo $category->name ?>">
                <small class="text-muted"><?php _e('Input forum name. It should be less than four words. It is a category name.', 'k-forum')?></small>
            </fieldset>

            <fieldset class="form-group">
                <label for="ForumDesc"><?php _e('Forum description', 'k-forum')?></label>
                <textarea name="desc" class="form-control" id="ForumDesc" rows="3"><?php if ( $category ) echo $category->description ?></textarea>
                <small class="text-muted"><?php _e('Input forum description. It should be less than 100 words.', 'k-forum')?></small>
            </fieldset>


            <fieldset class="form-group">
                <label for="ForumParent"><?php _e('Parent Forum', 'k-forum')?></label>
                <select name="parent" class="form-control" id="ForumParent">
                    <option value="<?php echo $parent_category->term_id?>"><?php _e('Select Parent Forum', 'k-forum')?></option>
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

            <?php if ( $category ) : ?>
                <input type="submit" class="btn btn-primary" value="<?php _e('Update Forum', 'k-forum')?>">
            <?php else : ?>
                <input type="submit" class="btn btn-primary" value="<?php _e('Create Forum', 'k-forum')?>">
            <?php endif; ?>
            <button type="button" class="btn btn-secondary forum-create-cancel-button"><?php _e('Cancel', 'k-forum')?></button>

        </form>
    </div>

    <div class="forum-list">

        <button class="button btn-primary forum-create-button"><?php _e('Create Forum', 'k-forum')?></button>


        <h2><?php _e('Forum List', 'k-forum')?></h2>

        <div class="forum-list container">
            <div class="row">
                <div class="col-xs-6 col-sm-4">Category</div>
                <div class="col-xs-2 col-sm-1">Edit</div>
                <div class="col-xs-2 col-sm-1">Delete</div>
                <div class="col-xs-2 col-sm-1">Posts</div>
                <div class="col-xs-12 col-sm-5">Description</div>
            </div>
            <?php
            if ( $categories ) {
            foreach($categories as $category) {
                ?>
                <div class="row">
                    <div class="col-xs-6 col-sm-4">
                        <a href="<?php echo forum()->listURL($category->slug)?>" target="_blank">
                            <?php
                            $pads = str_repeat( '&nbsp;&nbsp;', $category->depth );
                            echo $pads;
                            ?>
                            <?php echo $category->name?>
                        </a>
                    </div>
                    <div class="col-xs-2 col-sm-1"><a href="<?php echo forum()->adminURL()?>&category_id=<?php echo $category->term_id?>">Edit</a></div>
                    <div class="col-xs-2 col-sm-1"><a href="<?php echo forum()->doURL('forum_delete')?>&category_id=<?php echo $category->term_id?>">Delete</a></div>
                    <div class="col-xs-2 col-sm-1"><?php echo $category->count?></div>
                    <div class="col-xs-12 col-sm-5"><?php echo $category->description?></div>
                </div>
            <?php } } ?>

        </div>
    </div>
</div>
