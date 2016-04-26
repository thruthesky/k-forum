<?php
wp_enqueue_style( 'font-awesome', FORUM_URL . 'css/font-awesome/css/font-awesome.min.css' );




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

        
        포스팅 명칭, 사용자 아이디, 비밀번호, endpoint, 블로그 아이디
        <style>
            .api-settings .name input {
                width: 120px;
            }
            .api-settings .username input,
            .api-settings .password input {
                width: 80px;
            }
        </style>
        <script>
            jQuery(function($){
                $('.load-blog-id').click(function(){
                    var $this = $(this);
                    var $tr = $this.parent();
                    var $blogID = $tr.find('.blogID');
                    $blogID.append('<i class="fa fa-cog fa-spin fa-fw margin-bottom"></i>');
                    var url_endpoint = "<?php echo home_url("forum/submit")?>";

                    var url = url_endpoint + '?do=blogger_getUsersBlogs' +
                        '&username=' + $tr.find('.username').find('input').val() +
                        '&password=' + $tr.find('.password').find('input').val() +
                        '&endpoint=' + encodeURIComponent( $tr.find('.endpoint').find('input').val() ) +
                        '';
                    console.log(url);

                    $.get(url, function(re) {
                        console.log(re.length);
                        for ( var i in re ) {
                            var blog = re[i];
                            console.log(blog);
                            var m = '' +
                                '<div class="select-blog-id" blogid="'+blog['blogid']+'">' +
                                '   <span>('+blog['blogid']+')</span>' +
                                '   <span>' + blog['blogName'] + '</span>' +
                                '</div>' +
                                '' +
                                '';
                            $blogID.append( m );
                        }
                        $blogID.find('.fa-spin').remove();
                    });

                });
                $('body').on('click', '.select-blog-id', function() {
                    var $this = $(this);
                    var blogid = $this.attr('blogid');
                    $this.parent().find('input').val( blogid );
                });
            });
        </script>
        <table class="api-settings">
            <?php for ( $i = 0; $i < 10; $i ++ ) { ?>
            <tr valign="top">
                <td class="name"><input type="text" name="k_forum[blog_apis][<?php echo $i?>][name]" value="<?php if ( isset($value['blog_apis'][$i]['name']) ) echo $value['blog_apis'][$i]['name']?>"></td>
                <td class="username"><input type="text" name="k_forum[blog_apis][<?php echo $i?>][username]" value="<?php if ( isset($value['blog_apis'][$i]['username']) ) echo $value['blog_apis'][$i]['username']?>"></td>
                <td class="password"><input type="text" name="k_forum[blog_apis][<?php echo $i?>][password]" value="<?php if ( isset($value['blog_apis'][$i]['password']) ) echo $value['blog_apis'][$i]['password']?>"></td>
                <td class="endpoint"><input type="text" name="k_forum[blog_apis][<?php echo $i?>][endpoint]" value="<?php if ( isset($value['blog_apis'][$i]['endpoint']) ) echo $value['blog_apis'][$i]['endpoint']?>"></td>
                <td class="load-blog-id"><i class="fa fa-refresh"></i></td>
                <td class="blogID"><input type="text" name="k_forum[blog_apis][<?php echo $i?>][blogID]" value="<?php if ( isset($value['blog_apis'][$i]['blogID']) ) echo $value['blog_apis'][$i]['blogID']?>"></td>
            </tr>
            <? } ?>
        </table>

        <textarea name="k_forum[blog_footer]"><?php echo $value['blog_footer']?></textarea>
        <input type="submit">
    </form>
</div>

