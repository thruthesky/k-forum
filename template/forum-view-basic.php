<?php
/**
 * The template for displaying all single posts and attachments
 *
 *
 */

get_header();
if ( ! have_posts() ) {
    // If it comes here, it is an error.
}
the_post();


$category = current(get_the_category());

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="title">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </div>

            <a href="<?php echo home_url()?>/forum/<?php echo $category->slug?>">
                <?php echo $category->name?>
                </a>

            <div class="info">
                No. : <?php the_ID()?>,
                Count of Viewers : <?php  echo post()->increaseNoOfView( get_the_ID() )?>
                <a href="<?php echo home_url()?>/forum/<?php the_ID()?>/edit">글 수정</a>
                <a href="<?php echo home_url()?>/forum/<?php echo $category->slug?>">글 목록</a>
                <a href="<?php echo forum()->doURL('post_delete&id=' . get_the_ID() )?>">글 삭제</a>
            </div>


            <div class="content">
                <?php
                the_content();

                //if ( '' !== get_the_author_meta( 'description' ) ) include 'biography.php';
                ?>
            </div>

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }

            ?>


    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>

