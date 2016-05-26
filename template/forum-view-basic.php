<?php
/**
 * The template for displaying all single posts and attachments
 *
 *
 */

get_header();
wp_enqueue_style( 'forum-view-basic', FORUM_URL . 'css/forum-view-basic.css' );
if ( ! have_posts() ) {
    // If it comes here, it is an error.
}
the_post();


$category = current(get_the_category());


?>

<div id="primary" class="content-area forum">

    <main id="main" class="site-main" role="main">

        <?php include FORUM_PATH . '/template/social-buttons.php';?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="title">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </div>

            <div class="forum-title">
                <a href="<?php echo home_url()?>/forum/<?php echo $category->slug?>">
                    <?php echo $category->name?>
                </a>
            </div>

            <div class="meta container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <?php printf(__('By %s', 'k-forum'), get_the_author())?>
                        /
                        <?php printf( __('No. : %s', 'k-forum'), get_the_ID()); ?>
                        Count of Viewers : <?php  echo post()->increaseNoOfView( get_the_ID() )?>
                    </div>
                    <div class="buttons col-xs-12 col-sm-6">
                        <a class="btn btn-secondary btn-sm" href="<?php echo forum()->editURL( get_the_ID() ) ?>">글 수정</a>
                        <a class="btn btn-secondary btn-sm"href="<?php echo home_url()?>/forum/<?php echo $category->slug?>">글 목록</a>
                        <a class="btn btn-secondary btn-sm"href="<?php echo forum()->doURL('post_delete&id=' . get_the_ID() )?>">글 삭제</a>
                    </div>
                </div>
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

