<?php
wp_enqueue_style( 'font-awesome', FORUM_URL . 'css/font-awesome/css/font-awesome.min.css' );
wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css' );
wp_enqueue_script( 'tether', FORUM_URL . 'js/tether.min.js' );
wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js' );

$cat = get_category_by_slug(FORUM_CATEGORY_SLUG);
$categories = lib()->get_categories_with_depth( $cat->term_id );
?>


<div class="wrap k-forum">

    <h2><?php _e('K-Forum', 'k-forum')?></h2>

    <div>
        <?php _e('This is K forum.', 'k-forum')?>
    </div>

    <h2><?php _e('Create a Forum', 'k-forum')?></h2>

    <form action="<?php echo forum()->doURL('forum_create')?>" method="post">
        <fieldset class="form-group">
            <label for="ForumID">
                <?php _e('Forum ID', 'k-forum')?>
            </label>
            <input id='ForumID' class='form-control' type="text" name="id" placeholder="<?php _e('Please input forum ID', 'k-forum')?>">
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
        <input type="submit">
    </form>
    <?php

    ?>

    <h2><?php _e('Forum List', 'k-forum')?></h2>
    <?php

    foreach($categories as $category) {
        echo '<p>Category: <a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </p> ';
        echo '<p> Description:'. $category->description . '</p>';
        echo '<p> Post Count: '. $category->count . '</p>';
    }
    ?>

</div>
