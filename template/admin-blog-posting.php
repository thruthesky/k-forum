<?php





?>

<style>
    .k-forum form textarea {
        width: 100%;
        height: 10em;
    }
</style>
<div class="wrap k-forum">
    <h2><?php _e('K-Forum Blog Posting', 'k-forum')?></h2>

    홈페이지 글을 삭제하시면 블로그 글도 같이 삭제가 됩니다.


    <form method="post" action="options.php">
        <?php settings_fields( 'k_forum' ); ?>
        <?php $value = get_option( 'k_forum')?>
        <textarea name="k_forum[blog_header]"><?php echo $value['blog_header']?></textarea>
        * 블로그이름 endpoint blogid username password<br>
        * 시작이 # 이라면, 포스팅 되지 않습니다.
        <textarea name="k_forum[blog_apis]"><?php echo $value['blog_apis']?></textarea>
        <textarea name="k_forum[blog_footer]"><?php echo $value['blog_footer']?></textarea>
        <input type="submit">
    </form>
</div>

