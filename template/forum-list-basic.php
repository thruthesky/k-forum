<?php
get_header();

$categories = get_the_category();
if ( empty($categories) ) {
    $category = get_category_by_slug( segment(2) );
    $category_id = $category->term_id;

}
else $category_id = $categories[0]->term_id;
?>

    <main id="posts">


        <h2>Forum List</h2>
        <div class="post-new-button">
            <a href="<?php echo home_url()?>/forum/qna/edit">POST NEW</a>
        </div>

        <table>
            <thead>
            <th class="title">Title</th>
            <th class="author">Author</th>
            <th class="date">Date</th>
            <th class="no-of-view" title="No. of Views">View</th>
            </thead>
            <tbody>
            <?php
            if ( have_posts() ) : while( have_posts() ) : the_post();
                ?>
                <tr data-post-id="<?php the_ID()?>">
                    <td class="title">
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
                    </td>
                    <td class="author"><div><?php the_author()?></div></td>
                    <td class="date"><div title="<?php echo get_the_date()?>"><?php post()->the_date()?></div></td>
                    <td class="no-of-view"><div><?php echo number_format(post()->getNoOfView( get_the_ID() ) )?></div></td>
                </tr>
                <?php
            endwhile; endif;
            ?>
            </tbody>
        </table>


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
