<?php
$cat = get_category_by_slug(FORUM_CATEGORY_SLUG);
$args = array('child_of' => $cat->term_id);
$categories = get_categories( $args );
?>
<div class="wrap">
    <h2><?php _e('Forum List', 'k-forum')?></h2>
<?php
foreach($categories as $category) {
    echo '<p>Category: <a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a> </p> ';
    echo '<p> Description:'. $category->description . '</p>';
    echo '<p> Post Count: '. $category->count . '</p>';
}
?>

</div>