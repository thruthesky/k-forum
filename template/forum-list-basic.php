<?php
get_header();
$categories = get_the_category();
if ( empty($categories) ) {
    $category = get_category_by_slug( seg(1) );
    $category_id = $category->term_id;
}
else $category_id = $categories[0]->term_id;
?>

    <main id="posts">


        <h2>Forum List</h2>
        <div class="post-new-button">
            <a href="<?php echo home_url()?>/forum/<?php echo seg('1')?>/edit">POST NEW</a>
        </div>

        <div class="container post-list">
            <div class="row header">
                <div class="col-xs-12 col-sm-6 col-md-8 title">Title</div>
                <div class="col-xs-4 col-sm-2 col-md-2 author">Author</div>
                <div class="col-xs-4 col-sm-2 col-md-1 date">Date</div>
                <div class="col-xs-4 col-sm-2 col-md-1 no-of-view" title="No. of Views">View</div>
            </div>
            <?php
            if ( have_posts() ) : while( have_posts() ) : the_post();
                ?>
                <div class="row post" data-post-id="<?php the_ID()?>">
                    <div class="col-xs-12 col-sm-6 col-md-8  title">
                        <h2>
                            <a href="<?php echo esc_url( get_permalink() )?>">
                                <?php
                                $content = get_the_title();
                                if ( strlen( $content ) > 100 ) {
                                    $content = substr( get_the_title(), 0, strpos(get_the_title(), ' ', 100) );
                                }
                                echo $content;
                                ?>
                                <span class="title-no-of-view"><?php
                                    $count = wp_count_comments( get_the_ID() );
                                    if ( $count->approved )  echo "({$count->approved})";
                                    ?></span>
                                <?php
                                if ( post()->getNoOfImg( get_the_content() ) ) {
                                    echo '<span class="dashicons dashicons-format-gallery"></span>';
                                }
                                ?>
                            </a>
                        </h2>
                    </div>
                    <div class="col-xs-4 col-sm-2 col-md-2 author"><?php the_author()?></div>
                    <div class="col-xs-4 col-sm-2 col-md-1 date" title="<?php echo get_the_date()?>"><?php post()->the_date()?></div>
                    <div class="col-xs-4 col-sm-2 col-md-1 no-of-view"><?php echo number_format(post()->getNoOfView( get_the_ID() ) )?></div>
                </div>
                <?php
            endwhile; endif;
            ?>
        </div>


        <?php


        // Previous/next page navigation.
        the_posts_pagination( array(
            'mid_size'              => 5,
            'prev_text'             => 'PREV',
            'next_text'             => 'NEXT',
            'before_page_number'    => '[',
            'after_page_number'     => ']',

        ) );

        ?>
    </main>
<?php

get_footer();
