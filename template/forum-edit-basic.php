<?php
get_header();
wp_enqueue_style( 'forum-edit-basic', FORUM_URL . 'css/forum-edit-basic.css' );
wp_enqueue_script( 'forum-edit-basic', FORUM_URL . 'js/forum-edit-basic.js' );
$cat_desc = null;
if ( is_numeric(seg(1) ) ) {
    $post = get_post(seg(1));
    $category = current(get_the_category( $post->ID ));
    $category_id = $category->term_id;
    $cat_desc = $category->description;
}
else {
    $post = null;
    $category = get_category_by_slug( seg(1) );
    $category_id = $category->term_id;
}

?>



    <script>
        var url_endpoint = "<?php echo home_url("forum/submit")?>";
        var max_upload_size = <?php echo wp_max_upload_size();?>;
    </script>


    <section id="post-new">

        <div class="post-edit-meta">
            <div class="top">
                <h1 class="forum-title"><?php echo $category->name?></h1>
                <div class="forum-description"><?php echo $cat_desc?></div>
            </div>
        </div>


        <form action="<?php echo home_url("forum/submit")?>" method="post" enctype="multipart/form-data">

            <input type="hidden" name="do" value="post_create">
            <?php if ( $post ) : ?>
                <input type="hidden" name="id" value="<?php echo $post->ID?>">
            <?php endif; ?>
            <input type="hidden" name="category_id" value="<?php echo $category_id?>">
            <input type="hidden" name="file_ids" value="">
            <label for="title" style="display: none;">Title</label>
            <div class="text">
                <input type="text" id="title" name="title" value="<?php echo $post ? esc_attr($post->post_title) : ''?>" placeholder="<?php _e('Please input title', 'k-forum')?>">
            </div>

            <label for="content" style="display: none;">Content</label>
            <div class="text">
                <?php
                if ( $post ) {
                    $content = $post->post_content;
                }
                else {
                    $content = '';
                }
                $editor_id = 'new-content';
                $settings = array(
                    'textarea_name' => 'content',
                    'media_buttons' => false,
                    'textarea_rows' => 4,
                    'quicktags' => false
                );
                wp_editor( $content, $editor_id, $settings );

                ?>

            </div>

            <?php
            $attachments = forum()->markupEditAttachments( get_the_ID() );
            ?>

            <div class="photos"><?php echo $attachments['images']?></div>
            <div class="files"><?php echo $attachments['attachments']?></div>

            <div class="buttons">
                <div class="file-upload">
                    <i class="fa fa-camera"></i>
                    <span class="text"><?php _e('Choose File', 'k-forum')?></span>
                    <input type="file" name="file" onchange="forum.on_change_file_upload(this);" style="opacity: .001;">
                </div>
                <div class="right">


                    <label for="post-submit-button"><input id="post-submit-button" class="btn btn-primary btn-sm" type="submit" value="<?php _e('POST SUBMIT', 'k-forum')?>"></label>

                    <label class="begin-pro"><button class="btn btn-secondary btn-sm" type="button"><?php _e('Professional Writing', 'k-forum')?></button></label>
                    <label class="end-pro" style="display:none;"><button class="btn btn-secondary btn-sm" type="button"><?php _e('End Professional Writing', 'k-forum')?></button></label>

                    <label for="post-cancel-button"><a href="<?php echo forum()->listURL( $category->slug )?>" id="post-cancel-button" class="btn btn-secondary-outline btn-sm"><?php _e('Cancel', 'k-forum')?></a></label>
                </div>
            </div>

            <div class="loader">
                <img src="<?php echo FORUM_URL ?>/img/loader14.gif">
                File upload is in progress. Please wait.
            </div>

        </form>


    </section>
    <section id="pro">
        <style scoped>
            .good { color: #3a8c45; font-weight: bold; }
            .worse { color: orangered; font-weight: bold; }
            .worst { color: red; font-weight: bold; }
        </style>
        <div class="status">
            <ul>
                <li class="good"><?php _e('Good', 'k-forum')?></li>
                <li class="worse"><?php _e('Worse', 'k-forum')?></li>
                <li class="worst"><?php _e('Worst', 'k-forum')?></li>
            </ul>
        </div>
        <div>
        제목 단어 수 : <span class="count-title-words">0</span>
            제목의 키워드 수 : <span class="count-keyword-on-title">0</span>
        내용 단어 수 : <span class="count-content-words">0</span>
            내용의 키워드 수 : <span class="count-keyword-on-content">0</span>
        </div>


        <input type="text" name="keyword" value="" placeholder="Input keyword">

        <ul class="check-list">
            <li class="input-title"><?php _e('Input title', 'k-forum')?></li>
            <li class="input-more-words-on-title">제목을 8 단어 이상으로 입력하십시오.</li>
            <li class="input-less-words-on-title">제목을 20 단어 이하로 입력하십시오.</li>
            <li class="input-content">내용을 입력하십시오.</li>
            <li class="input-more-words-on-content">내용에 300 단어 이상 입력하십시오.</li>

            <li class="input-more-words-on-keyword">키워드를 두 글자 이상 입력하십시오.</li>
            <li class="input-less-words-on-keyword">키워드를 두 단어 이하로 입력하십시오.</li>

            <li class="input-keyword-on-title">제목에 키워드를 입력하십시오.</li>
            <li class="input-less-keyword-on-title">제목에 키워드를 2 회 이하로 입력하십시오.</li>

            <li class="input-minimum-two-keyword-on-content">내용에 최소한 2 개의 키워드를 입력하십시오.</li>
            <li class="input-more-keyword-on-content">내용에 키워드가 너무 적게 입력되었습니다.
                적당한 키워드 회 수 :
                    최소(<span class="min-count-keyword-on-content"></span>)
                    최대(<span class="max-count-keyword-on-content"></span>)
                , 현재 회 수 : <span class="count-keyword-on-content"></span>
            </li>
            <li class="input-less-keyword-on-content">내용에 키워드가 너무 많이 입력되었습니다.
                적당한 키워드 회 수 :
                최소(<span class="min-count-keyword-on-content"></span>)
                최대(<span class="max-count-keyword-on-content"></span>)
                , 현재 회 수 : <span class="count-keyword-on-content"></span>
            </li>
            <li class="input-image">이미지를 등록합십시오.</li>
            <li class="input-keyword-on-image-alt">이미지 설명(ALT)에 키워드를 포함하십시오.</li>


        </ul>

        <hr>
        <ul>
            <li>네이버에 글 자동 등록.</li>
            <li>Google + 에 글 자동 등록.</li>
            <li>네이버 웹마스터툴 & 구글 웹 마스터 툴 & 구글 애널리스틱스 링크.</li>
            <li>
                구글, 네이버, 다음에
                도메인으로 검색 결과 수. 얼마나 많이 색인이 되었는지 확인.
            </li>
            <li>게시판 별 rss feed, 전체 게시판 별 rss feed</li>
            <li>카카오톡 로그인</li>
            <li>메타 정보 : OG Tag 입력(제목, 내용, URL 등). Featured Image 선택 또는 업로드.</li>
            <li>글 제목
                10 단어에서 20단어 이하.
                키워드 포함.
            </li>
            <li>내용 300 단어 이상.</li>
            <li>키워드 입력.
                키워드와 텍스트 용량에 비해서 키워드 표시 회수.
                키워드 제목 표시.
                키워드 첫줄에 표시.
                키워드 첫번째 단락에 표시.
                메타 정보 제목, 내용, URL 등에 키워드 표시.
                이미지 ALT 에 키워드 표시.
            </li>
            <li>이미지 1개 이상 등록.</li>
            <li>링크 1개 이상 등록.</li>
            <li>H1 태그 1개 등록. 키워드 포함.</li>
        </ul>

        <ul>
            팁
            <li>내용의 길이가 1천 단어 이상이면 좋음.</li>
            <li>내용의 단어 수는 실제 단어와 약간의 차이가 발생 할 수 있습니다. (내부적으로 HTML 로 라인 구분이 되지만, HTML 제거하면 마지막 글자와 다음 라인 글자가 붙어서 한단어가 됨)</li>
        </ul>



    </section>
<?php
get_footer();
?>