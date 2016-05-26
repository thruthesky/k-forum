<?php

/**
 * Class forum
 * @file forum.php
 * @desc K forum.
 *
 *
 * @todo security issue. (1) check admin permission on admin action like post_create().
 */
class forum
{
    public function __construct()
    {

    }

    public function setNone404() {
        global $wp_query;
        if ( $wp_query->is_404 ) {
            status_header( 200 );
            $wp_query->is_404=false;
        }
    }

    public function init()
    {
        return $this;
    }

    public function loadText() {
        load_plugin_textdomain( 'k-forum', FALSE, basename( dirname( FORUM_FILE_PATH ) ) );
        return $this;
    }

    /**
     *
     *
     * Does the default works ( creating FORUM_CATEGORY_SLUG, etc ) for the forum activation.
     *
     * @Attention This method is only called when the admin or 'multisite admin' accesses 'admin page'.
     *
     * @note it adds routes here. This registers the 'routes' like "/forum/qna"
     *
     * @return $this
     */
    public function doDefaults() {

        klog("doDefaults()");

        // forum()->addRoutes(); // addRoutes on it does the default data insert ( on activation )


        $category = get_category_by_slug(FORUM_CATEGORY_SLUG);
        if ( ! $category ) {


            if (!function_exists('wp_insert_category')) require_once(ABSPATH . "/wp-admin/includes/taxonomy.php");

            $catarr = array(
                'cat_name' => __('K-Forum', 'k-forum'),
                'category_description' => __("This is K forum.", 'k-forum'),
                'category_nicename' => FORUM_CATEGORY_SLUG,
            );
            $ID = wp_insert_category($catarr, true);
            if (is_wp_error($ID)) wp_die($ID->get_error_message());

            $catarr = array(
                'cat_name' => __('Welcome', 'k-forum'),
                'category_description' => __("This is Welcome forum", 'k-forum'),
                'category_nicename' => 'welcome-' . date('his'), // @note When or for some reason, when k-forum and its category was deleted, it must create a new slug. ( guess this is because the permalink or route is already registered. )
                'category_parent' => $ID,
            );
            $ID = wp_insert_category($catarr, true);
            if (is_wp_error($ID)) wp_die($ID->get_error_message());
        }

        /**
         *
         *
         * @note since wp_insert_post prints out header, this will cause 'Header sent' error message.
         *
         *
        forum()->post_create([
        'post_title'    => __('Welcome to K forum - name', 'k-forum'),
        'post_content'  => __('This is a test post in welcome K forum.', 'k-forum'),
        'post_status'   => 'publish',
        'post_author'   => wp_get_current_user()->ID,
        'post_category' => array( $ID )
        ]);
         *
         */

        $this->save_forum_slugs_into_option();

        return $this;
    }

    public function enqueue()
    {
        add_action( 'wp_enqueue_scripts', function() {
            //wp_enqueue_style( 'dashicons' );
            wp_enqueue_script( 'wp-util' );
            wp_enqueue_script( 'jquery-form' );
            wp_enqueue_style( 'forum-basic', FORUM_URL . 'css/forum-basic.css' );
            wp_enqueue_script( 'forum', FORUM_URL . 'js/forum.js' );
            wp_enqueue_script( 'underscorestring', FORUM_URL . 'js/underscore.string.min.js' );
            wp_enqueue_style( 'font-awesome', FORUM_URL . 'css/font-awesome/css/font-awesome.min.css' );

            wp_enqueue_style( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css' );
            wp_enqueue_script( 'tether', FORUM_URL . '/js/tether.min.js' );
            wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js' );

            wp_enqueue_script( 'cookie', FORUM_URL . 'js/js.cookie.min.js' );

        });
        return $this;
    }

    private function loadTemplate($file)
    {


        $new_template = locate_template( array( $file ) );
        if ( '' != $new_template ) {
            return $new_template ;
        }
        else {
            return FORUM_PATH . "template/$file";
        }
    }


    /**
     *
     * This method is called by 'http://abc.com/forum/submit' with $_REQUEST['do']
     *
     * Use this function to do action like below that does not display data to web browser.
     *
     *  - ajax call
     *  - submission without display data and redirect to another page.
     *
     *
     * @Note This method can only call a method in 'forum' class.
     *
     *
     */
    private function submit()
    {
        if ( ! isset($_REQUEST['do']) || empty($_REQUEST['do']) ) {
            echo "<h2>method name is empty</h2>";
        }
        else {
            $do_list = [
                'forum_create', 'forum_delete',
                'post_create', 'post_delete',
                'comment_create', 'comment_delete',
                'file_upload', 'file_delete',
                'blogger_getUsersBlogs',
                'login',
            ];
            if ( in_array( $_REQUEST['do'], $do_list ) ) $this->$_REQUEST['do']();
            else echo "<h2>You cannot call the method - '$_REQUEST[do]' because the method is not listed on 'do-list'.</h2>";

        }
        exit;
    }

    /**
     *
     * Returns true if the log-in user can blog.
     *
     *
     *
     * @return bool
     *
     *      - false if there is no blog info.
     *      - false if admin permission is set and the login user is not admin.
     */
    public function user_can_blog()
    {
        $apis = forum()->parseBlogSetting();
        if ( empty($apis) ) return false;

        $value = get_option('k_forum');
        if ( ! isset( $value['blog_permission'] ) ) return true;

        $who = $value['blog_permission'];

        if ( $who == 'admin' ) {
            if ( current_user_can('administrator') || is_admin() ) {
                return true;
            }
        }

        return false;
    }


    /**
     * Creates a new post or Edits an existing post.
     *
     * @param array $post_arr
     * @todo permission check. if it is update, then check the updator's ID.
     */
    private function post_create( $post_arr = array() ) {

        $is_update = isset($_REQUEST['id']) ? true : false;
        if ( empty($post_arr) ) {
            $post_arr = array(
                'post_title'    => $_REQUEST['title'],
                'post_content'  => $_REQUEST['content'],
                'post_status'   => 'publish',
                'post_author'   => wp_get_current_user()->ID,
                'post_category' => array( $_REQUEST['category_id'] )
            );
        }

        klog( $post_arr );


        if ( $is_update ) {         // update
            $this->checkOwnership($_REQUEST['id']);
            $post_arr['ID'] = $_REQUEST['id'];
            $post_ID = wp_update_post($post_arr);
        }
        else {                                  // insert ( create a new post )
            $this->checkLogin();
            // Insert the post into the database
            $post_ID = wp_insert_post( $post_arr );
        }

        if ( is_wp_error( $post_ID ) ) {
            echo $post_ID->get_error_message();
            exit;
        }

        // save SEO keyword
        delete_post_meta($post_ID, 'keyword');
        add_post_meta($post_ID, 'keyword', $_REQUEST['keyword']);


        // blog posting. pos / edit
        $this->blogPosting($post_ID );


        $url = get_permalink( $post_ID );
        $this->updateFileWithPost($post_ID);
        $this->deleteFileWithNoPost();

        wp_redirect( $url ); // redirect to view the newly created post.
    }

    public function deleteFileWithNoPost()
    {
        $args = array(
            'post_type' => 'attachment',
            'author' => FORUM_FILE_WITH_NO_POST,
            'date_query' => array(
                array(
                    'column' => 'post_date',
                    'before' => '1 day ago',
                )
            ),
            'post_status'    => 'inherit',
            'posts_per_page' => -1,
        );
        $files = new WP_Query( $args );
        if ( $files->have_posts() ) {
            while ( $files->have_posts() ) {
                $files->the_post();
                //di( get_post() );
                if ( wp_delete_attachment( get_the_ID() ) === false ) {
                    // error
                }
            }
        }
        // wp_delete_post(); // why this code is here?
    }

    /**
     * Returns the URL of forum submit with the $method.
     *
     * The returned URL will will call the method.
     *
     * @param $method
     * @return string|void
     * @code
     *      <form action="<?php echo forum()->doURL('forum_create')?>" method="post">
     * @encode
     */
    public function doURL($method)
    {
        return home_url("/forum/submit?do=$method");
    }

    /**
     * Returns forum category.
     *
     *
     */
    public function getForumCategory()
    {
        $cat = get_category_by_slug(FORUM_CATEGORY_SLUG);
        /**
         * If there is no 'k-forum' post PostType category, then it do the defaults.
         */
        if ( empty($cat) ) {
            forum()->doDefaults();
            $cat = get_category_by_slug(FORUM_CATEGORY_SLUG);
        }
        return $cat;
    }




    /**
     *
     *
     *
     * @WARNING
     *
     *      1. It uses md5() to avoid of replacing same file name.
     *          Since it does not add 'tag' like '(1)', '(2) for files which has same file name.
     *
     *      2. It uses md5() to avoid character set problems. like some server does not support utf-8 nor ... Most of servers do not support spanish chars. some servers do not support Korean characters.
     *
     *      3. It uses md5() to avoid possible matters due to lack of developmemnt time.
     *
     */
    private function file_upload() {
        $file = $_FILES["file"];

        klog($file);

        // Sanitize filename.
        $filename = $file["name"];
        $filetype = wp_check_filetype( basename( $filename ), null );
        $sanitized_filename = lib()->sanitize_special_chars( $filename );

        // Get WordPress upload folder.
        $wp_upload_dir = wp_upload_dir();

        // Get URL and Path of uploaded file.
        $path_upload = $wp_upload_dir['path'] . "/$sanitized_filename";
        $url_upload = $wp_upload_dir['url'] . "/$sanitized_filename";

        if ( $file['error'] ) wp_send_json_error( lib()->get_upload_error_message($file['error']) );

        // Move the uploaded file into WordPress uploaded path.
        if ( ! move_uploaded_file( $file['tmp_name'], $path_upload ) ) wp_send_json_error( "Failed on moving uploaded file." );

        // Create a post of attachment.
        $attachment = array(
            'guid'           => $url_upload,
            'post_author'   => FORUM_FILE_WITH_NO_POST,
            'post_mime_type' => $filetype['type'],
            'post_title'     => $filename,
            'post_content'   => '',
            'post_status'    => 'inherit',
        );
        /**
         * This does not upload a file but creates a 'attachment' post type in wp_posts.
         *
         */
        $attach_id = wp_insert_attachment( $attachment, $filename );
        add_post_meta( $attach_id, 'author', wp_get_current_user()->ID );
        klog("attach_id: $attach_id");


        // Update post_meta for the attachment.
        // You do it and you can use get_attached_file() and get_attachment_url()
        // update_attached_file will update the post meta of '_wp_attached_file' which is the source of "get_attached_file() and get_attachment_url()"
        update_attached_file( $attach_id, $path_upload );




        wp_send_json_success([
            'attach_id' => $attach_id,
            'url' => $url_upload,
            'type' => $filetype['type'],
            'file' => $file,
        ]);
    }

    private function file_delete() {
        $id = $_REQUEST['id'];
        $path = get_attached_file( $id );
        if ( ! file_exists( $path ) ) {
            wp_send_json_error( new WP_Error('file_not_found', "File of ID $id does not exists. path: $path") );
        }
        // wp_delete_attachment() 는 attachment post 와 업로드 된 파일을 같이 삭제한다.
        if ( wp_delete_attachment( $id ) === false ) {
            wp_send_json_error( new WP_Error('failed_on_delete', "File of ID $id does not exists. path: $path") );
        }
        else {
            wp_send_json_success( array( 'id' => $id ) );
        }
    }

    public function addAdminMenu()
    {
        add_action( 'wp_before_admin_bar_render', function () {
            global $wp_admin_bar;
            $wp_admin_bar->add_menu( array(
                'id' => 'forum_toolbar',
                'title' => __('K-Forum', 'k-forum'),
                'href' => forum()->adminURL()
            ) );
        });

        add_action('admin_menu', function () {
            add_menu_page(
                __('K-Forum', 'k-forum'), // page title. ( web browser title )
                __('K-Forum', 'k-forum'), // menu name on admin page.
                'manage_options', // permission
                'k-forum/template/admin.php', // slug id. what to open
                '',
                'dashicons-text',
                '23.45' // list priority.
            );
            add_submenu_page(
                'k-forum/template/admin.php', // parent slug id
                __('Forum List', 'k-forum'),
                __('K-Forum List', 'k-forum'),
                'manage_options',
                'k-forum/template/admin-forum-list.php',
                ''
            );
            add_submenu_page(
                'k-forum/template/admin.php', // parent slug id
                __('Blog Posting', 'k-forum'),
                __('Blog Posting', 'k-forum'),
                'manage_options',
                'k-forum/template/admin-blog-posting.php',
                ''
            );
        } );

        return $this;
    }

    /**
     * @deprecated see addRoutes()
     */
    public function addRoutes() {
        $this->addRewrites();
    }

    /**
     * Add rewrite rules.
     *
     * @Warning This does not flush rules. So, it must be flushed on activation.
     *
     * 아래의 rewrite_rule 를 사용하지 않고도 template_include 를 통해서 template 을 포함 할 수 있다.
     *
     * 하지만 Main Loop 를 사용 할 수 없다.
     *
     * ReWrite 하는 목적은 Main Loop 를 사용 할 수 있도록 하기 위한 것이다.
     *
     *
     * @changed May 5, 2016. method name from 'addRoutes' to 'addRewrites'
     *
     */
    public function addRewrites()
    {

            add_rewrite_rule(
                '^forum/([^\/]+)/?$',
                'index.php?category_name=$matches[1]',
                'top'
            );
            add_rewrite_rule(
                '^forum/([^\/]+)/page/([0-9]+)/?$',
                'index.php?category_name=$matches[1]&paged=$matches[2]',
                'top'
            );
            add_rewrite_rule(
                '^forum/([^\/]+)/([0-9]+)?$',
                'index.php?category_name=$matches[1]&p=$matches[2]',
                'top'
            );
            //add_rewrite_tag('%val%','([^/]*)');
//            flush_rewrite_rules();
    }

    /**
     * Flushes the rewrite rules.
     * @Attention this code must be called only on activation.
     */
    public function flushRewrites() {
        $this->addRewrites();
        flush_rewrite_rules();
    }


    /**
     *
     * @param $parent_ID - is the parent post ID or parent comment ID.
     *
     * @WARNING since wp_posts table does not support for attachment for comment, it uses a trick.
     *
     * wp_posts.post_parent can hold numeric value from 0 to 18,446,744,073,709,551,615.
     * It considers if any number that is over 1,000,000,000 then it is a comment number.
     *
     */
    private function updateFileWithPost($parent_ID)
    {
        if ( ! isset($_REQUEST['file_ids']) ) return;
        $ids = $_REQUEST['file_ids'];
        $arr_ids = explode(',', $ids);
        if ( empty($arr_ids) ) return;
        foreach( $arr_ids as $id ) {
            if ( empty($id) ) continue;
            $author_id = get_post_meta($id, 'author', true);
            wp_update_post(['ID'=>$id, 'post_author' => $author_id, 'post_parent'=>$parent_ID]);
            delete_post_meta( $id, 'author', $author_id);
        }
    }

    public function adminURL()
    {
        return home_url('wp-admin/admin.php?page=k-forum%2Ftemplate%2Fadmin.php');
    }


    /**
     * Creates / Updates a forum.
     */
    private function forum_create() {

        if ( ! function_exists('wp_insert_category') ) require_once (ABSPATH . "/wp-admin/includes/taxonomy.php");

        if ( isset( $_REQUEST['parent'] ) && $_REQUEST['parent'] ) {
            $parent = $_REQUEST['parent'];
        }
        else $parent = get_category_by_slug( FORUM_CATEGORY_SLUG )->term_id;

        $catarr = array(
            'cat_name' => $_REQUEST['name'],
            'category_description' => $_REQUEST['desc'],
            'category_nicename' => $_REQUEST['id'],
            'category_parent' => $parent,
        );

        if ( isset($_REQUEST['category_id']) ) $catarr['cat_ID'] = $_REQUEST['category_id'];

        $term_ID = wp_insert_category( $catarr, true );

        if ( is_wp_error( $term_ID ) ) wp_die($term_ID->get_error_message());


        $this->save_forum_slugs_into_option();


        delete_term_meta( $term_ID, 'template' );
        add_term_meta( $term_ID, 'template', $_REQUEST['template'], true );

        wp_redirect( $this->adminURL() );
    }


    /**
     * Saves slugs of the forum into option for easy use.
     *
     * @changed May 5, 2015. method name changed from update_forum_slugs to save_forum_slugs_into_option
     * @changed May 5, 2016. it does not rewrite_rules for "/qna" or "/freetalk".
     */
    private function save_forum_slugs_into_option() {

        /**
         * @note Remember ( Stores ) slugs of k-forum into option.
         *
         */
        $category = get_category_by_slug( FORUM_CATEGORY_SLUG );
        if ( empty($category) ) return;

        $args = array(
            'child_of'                 => $category->term_id,
            'hide_empty'               => FALSE,
        );
        $child_categories = get_categories($args );
        $slugs = [];
        foreach ( $child_categories as $child ) {
            $slugs[] = $child->slug;

            /**
             *
             *
            $slug = '^' . $child->slug . '$';
            //di($slug);
            add_rewrite_rule(
                $slug,
                'index.php?category_name='.$child->slug,
                'top'
            );
            */
            flush_rewrite_rules();
        }
        update_option('forum-slugs', $slugs);
    }


    private function forum_delete() {
        if ( ! function_exists('wp_insert_category') ) require_once (ABSPATH . "/wp-admin/includes/taxonomy.php");
        //wp_delete_category();
        $category = get_category( $_REQUEST['category_id']);
        wp_insert_category([
            'cat_ID' => $category->term_id,
            'cat_name' => "Deleted : " . $category->name,
            'category_parent' => 0,
        ]);
        wp_redirect( $this->adminURL() );
    }

    /**
     * @todo permission check
     */
    private function post_delete() {
        $id = $_REQUEST['id'];
        $categories = get_the_category($id);

        $this->checkOwnership($id);


        // 1. delete blog post
        $apis = $this->parseBlogSetting();
        foreach ( $apis as $api ) {
            $blog_postID_key = "blog_postID_$api[name]";
            $blog_postID = get_post_meta( $id, $blog_postID_key, true);
            if ( $blog_postID ) {
                $re = rpc()->blogger_deletePost($api['endpoint'], $api['username'], $api['password'], $blog_postID);
                if ( ! $re ) {
                    klog("error on blogger_delete");
                }
            }
        }

        // 2. delete files
        $attachments = get_children( ['post_parent' => $id, 'post_type' => 'attachment'] );
        foreach ( $attachments  as $attachment ) {
            wp_delete_attachment( $attachment->ID, true );
        }

        // 3. delete post
        wp_delete_post($id, true);


        // move to forum list.
        if ( ! $categories || is_wp_error( $categories ) ) {
            wp_redirect( home_url() );
        }
        else {
            $category = current($categories);
            wp_redirect( forum()->listURL($category->slug));
        }



    }

    /**
     *
     *
     *
     *
     */
    private function comment_delete() {
        $comment_ID = $_REQUEST['comment_ID'];

        $this->checkOwnership( $comment_ID, 'comment' );

        $comment = get_comment( $comment_ID );
        $post = get_post( $comment->comment_post_ID );

        // delete files
        $attachments = get_children( ['post_parent' => FORUM_COMMENT_POST_NUMBER + $comment_ID, 'post_type' => 'attachment'] );

        foreach ( $attachments  as $attachment ) {
            wp_delete_attachment( $attachment->ID, true );
        }

        wp_delete_comment( $comment_ID );

        wp_redirect( get_permalink( $post ) );

    }

    public function get_files( $post_ID ) {
        if ( empty( $post_ID ) ) return null;
        return get_children( ['post_parent' => $post_ID, 'post_type' => 'attachment'] );
    }


    /**
     * Get the src of first image of the post
     * @param $post_ID
     * @return null
     */
    public function get_first_image ( $post_ID ) {
        $files = $this->get_files( $post_ID );
        $files = array_reverse( $files );
        if ( ! $files || is_wp_error($files) ) return null;
        foreach ( $files as $file ) {
            if ( strpos( $file->post_mime_type, 'image' ) !== false ) { // image
                return $file->guid;
            }
        }
        return null;
    }

    /**
     * Get the src of last image of the post
     * @param $post_ID
     * @return null
     */
    public function get_last_image ( $post_ID ) {
        $files = $this->get_files( $post_ID );
        if ( ! $files || is_wp_error($files) ) return null;
        foreach ( $files as $file ) {
            if ( strpos( $file->post_mime_type, 'image' ) !== false ) { // image
                return $file->guid;
            }
        }
        return null;
    }


    /**
     * Returns HTML markup for display images and attachments.
     *
     * Use this method to display images and attachments.
     *
     * @param $post_ID
     *
     * @return string
     */
    public function markupEditAttachments( $post_ID ) {

        $files = $this->get_files( $post_ID );
        if ( ! $files || is_wp_error($files) ) return null;

        $images = $attachments = null;
        foreach ( $files as $file ) {
            $m = "<div class='attach' attach_id='{$file->ID}' type='{$file->post_mime_type}'>";
            if ( strpos( $file->post_mime_type, 'image' ) !== false ) { // image
                $m .= "<img src='{$file->guid}'>";
                $m .= "<div class='delete'><span class='dashicons dashicons-trash'></span> Delete</div>";
                $m .= '</div>';
                $images .= $m;
            }
            else { // attachment
                $m .= "<a href='{$file->guid}'>{$file->post_title}</a>";
                $m .= "<span class='delete'><span class='dashicons dashicons-trash'></span> Delete</span>";
                $m .= '</div>';
                $attachments .= $m;
            }
        }

        return [ 'images' => $images, 'attachments' => $attachments ];
    }
    public function markupCommentAttachments( $comment_parent_ID ) {
        if ( empty( $comment_parent_ID ) ) return null;

        $files = get_children( ['post_parent' => $comment_parent_ID, 'post_type' => 'attachment'] );
        if ( ! $files || is_wp_error($files) ) return null;

        $images = $attachments = null;
        foreach ( $files as $file ) {
            $m = "<div class='attach' attach_id='{$file->ID}' type='{$file->post_mime_type}'>";
            if ( strpos( $file->post_mime_type, 'image' ) !== false ) { // image
                $m .= "<img src='{$file->guid}' class='img-thumbnail'>";
                $m .= '</div>';
                $images .= $m;
            }
            else { // attachment
                $m .= "<a href='{$file->guid}'>". sprintf( __('Download:', 'k-forum')) ." {$file->post_title}</a>";
                $m .= '</div>';
                $attachments .= $m;
            }
        }
        return [ 'images' => $images, 'attachments' => $attachments ];
    }

    public function listURL( $slug ) {
        return home_url() . "/forum/$slug";
    }

    public function addFilters()
    {



        /**
         *
         * Add routes for friendly URL.
         *
         * @Attention Do 'friendly URL routing ONLY IF it is necessary'.
         *
         *      - Don't do friendly URL routing on file upload submit, delete submit, vote submit, report submit.
         *      - Do friendly URL routing only if it is visible to user and search engine robot.
         *
         */
        add_filter( 'template_include', function ( $template ) {
            $this->setNone404(); // @todo ??






            /**
             * @note
             */
            $slugs = forum()->slugs();







            // forum list.
            // http://abc.com/forum/qna
            if ( seg(0) == 'forum' && seg(1) != null && seg(2) == null  ) {
                return $this->loadTemplate( $this->locateTemplate(seg(1), 'list') );
            }

            // forum pagination
            // http://domain.com/forum/qna/5 ==> 5 page.
            else if ( seg(0) == 'forum' && seg(1) != null && seg(2) == 'page' ) {
                return $this->loadTemplate( $this->locateTemplate(seg(1), 'list') );
            }

            // post edit
            // http://abc.com/forum/(xxxx)/edit
            // if (xxxx) is numeric, then it's 'post edit'
            // else it's 'new post'.
            else if ( seg(0) == 'forum' && seg(1) != null && seg(2) == 'edit'  ) {
                $s = seg(1);
                if ( is_numeric($s) ) $this->checkOwnership( $s ); // post edit
                else $this->checkLogin(); // post write

                return $this->loadTemplate( $this->locateTemplate(seg(1), 'edit') );
                //return $this->loadTemplate( 'forum-edit-basic.php' );
            }
            // comment edit
            // http://abc.com/forum/xxxx/commentEdit
            else if ( seg(0) == 'forum' && seg(1) != null && seg(2) == 'commentEdit'  ) {
                $this->checkOwnership(seg(1), 'comment');
                return $this->loadTemplate( $this->locateTemplate(seg(1), 'commentEdit') );
                //return $this->loadTemplate('forum-commentEdit-basic.php');
            }
            // view
            // https://abc.com/forum/forum-name/[0-9]+
            else if ( seg(0) == 'forum' && seg(1) != null && is_numeric(seg(2))  ) {
                return $this->loadTemplate( $this->locateTemplate(seg(1), 'view') );
            }
            // Matches if the post is under forum category.
            else if ( is_single() ) {
                klog("add_filter() : is_single()");
                $id = get_the_ID();
                if ( $id ) {
                    $categories = get_the_category( $id );
                    if ( $categories ) {
                        $category = current( $categories );
                        $category_id = $category->term_id;
                        klog("category_id: $category_id");
                        $ex = explode('/', get_category_parents($category_id, false, '/', true));
                        klog("category slug of the category id: $ex[0]");
                        if ( $ex[0] == FORUM_CATEGORY_SLUG ) {
                            //return $this->loadTemplate('forum-view-basic.php');
                            return $this->loadTemplate( $this->locateTemplate( $category->slug, 'view' ) );
                        }
                    }
                }
            }
            return $template;
        }, 0.01 );

        /**
         *
        add_filter('comment_form_submit_field', function($submit_field, $args) {
        $m = '';
        $m .= "<div>파일업로드</div>";
        return $m . $submit_field;
        }, 10, 2);

         */


        /**
         * Adds edit button on comment.
         */
        /*
        add_filter('comment_reply_link', function($link, $args, $comment, $post) {

            $url = $this->commentEditURL( $comment->comment_ID );

            $m = <<<EOM
<div class="post-edit" commend-id="{$comment->comment_ID}"><a href="$url">Edit</a></div>
EOM;

            return $m . $link;
        }, 10, 4);
        */



        /**
         * Filters on comments list arguments.
         */
        /*
                add_filter( 'wp_list_comments_args', function( $r ) {
                    $r['type'] = 'comment';
                    $r['callback'] = function($comment, $args, $depth) {
                        include FORUM_PATH . '/template/comment.php';
                    };
                    return $r;
                });
        */


        /**
         *
         */
        add_filter( 'comments_template', function( $comment_template ) {
            global $post;
            $categories = get_the_category( $post->ID );
            if ( $categories ) {
                $slug = current( $categories )->slug;
                if ( in_array( $slug, forum()->slugs() ) ) {

                    $template = $this->locateTemplate($slug, 'comments');
                    $comment_template = locate_template( $template );
                    //$comment_template = locate_template('forum-comments-basic.php');

                    if ( empty($comment_template) ) {
                        $comment_template = FORUM_PATH . "template/forum-comments-basic.php";
                    }
                }
            }
            return $comment_template;
        });


        return $this;
    }

    /**
     * @param $the_ID
     * @return string
     *
     * @code <a href="<?php echo post()->editURL( get_the_ID() ) ?>">글 수정</a>
     * @code <a href="<?php echo post()->editURL( get_the_ID() ) ?>">코멘트 수정</a>
     */
    public function editURL($the_ID)
    {
        return home_url() . "/forum/$the_ID/edit";
    }

    /**
     * @param $comment_ID
     * @return string
     * @code <a href="<?php echo post()->editURL( get_the_ID() ) ?>">글 수정</a>
     * @code <a href="<?php echo post()->editURL( get_the_ID() ) ?>">코멘트 수정</a>
     */
    public function commentEditURL($comment_ID)
    {
        return home_url() . "/forum/$comment_ID/commentEdit";
    }


    public function commentDeleteURL($comment_ID)
    {
        return forum()->doURL('comment_delete&comment_ID=' . $comment_ID );
    }

    public function manageRoles()
    {
        return $this;
    }

    /**
     * Creates a new comment or edit a comment.
     *
     *
     *
     */
    private function comment_create( ) {

        //
        if ( isset( $_REQUEST['comment_ID'] ) ) { // update
            $comment_ID = $_REQUEST['comment_ID'];
            $this->checkOwnership( $comment_ID, 'comment' );       // check comment owner.
            $comment = get_comment( $comment_ID );
            $post_ID = $comment->comment_post_ID;
            $re = wp_update_comment([
                'comment_ID' => $comment_ID,
                'comment_content' => $_REQUEST['comment_content']
            ]);


            if ( ! $re ) {
                // error or content has not changed.
            }
        }
        else { // new
            $this->checkLogin();
            $post_ID = $_REQUEST['comment_post_ID'];
            $comment_ID = wp_insert_comment([
                'comment_post_ID' => $post_ID,
                'comment_parent' => $_REQUEST['comment_parent'],
                'user_id' => wp_get_current_user()->ID,
                'comment_content' => $_REQUEST['comment_content'],
                'comment_approved' => 1,
            ]);
            if ( ! $comment_ID ) {
                wp_die("Comment was not created");
            }
        }

        $this->updateFileWithPost( FORUM_COMMENT_POST_NUMBER  + $comment_ID );

        $url = get_permalink( $post_ID ) . '#comment-' . $comment_ID ;

        wp_redirect( $url ); // redirect to view the newly created post.
    }

    /**
     * Returns array of forum slugs
     * @return array
     * @code Return code sample.
     * Array
    (
    [0] => discussion
    [1] => manila
    [2] => korea
    [3] => new-category
    [4] => abc
    [5] => qna
    )
     * @endcode
     */
    public function slugs()
    {
        $slugs = get_option('forum-slugs');
        if ( empty($slugs) ) {
            // This is an error.
            // This may happen when forum exists, but it was not updated on 'forum-slugs' like old version has no function on updating 'forum-slugs'.
            $this->save_forum_slugs_into_option();
            $slugs = get_option('forum-slugs');
            if ( empty($slugs) ) $slugs = array(); // 2016-05-18. PHP warning when empty when no forum.
        }
        return $slugs;
    }

    /**
     *
     * Insert forum data defaults when the admin enters in admin area.
     *
     *
     * @return $this
     */
    public function addHooks()
    {
        add_action('admin_init', function(){

        });

        add_action('init', function(){

            $this->addRewrites();

            $rules = $GLOBALS['wp_rewrite']->rewrite_rules();
            if ( isset($rules['^forum/([^\/]+)/?$']) ) {

            }
            else {
                echo "<h1>ERROR: No rewrite rule registered.</h1>";
            }



            /**
             *
             *
             * http://abc.com/forum/submit must be hooked here or it will not work on 4.5
             *
            // http://abc.com/forum/submit will take all action that does not need to display HTML to web browser.
            //
            // http://abc.com/forum/submit?do=file_upload
            // http://abc.com/forum/submit?do=file_delete
            // http://abc.com/forum/submit?do=post_delete
            // http://abc.com/forum/submit?do=post_vote
            // http://abc.com/forum/submit?do=post_report
            // etc...
             */
            if ( seg(0) == 'forum' && seg(1) == 'submit' ) {
                forum()->submit();
                exit;
            }

        });

        return $this;
    }

    public function parseBlogSetting()
    {
        $info = [];
        $value = get_option('k_forum');
        $setting = $value['blog_apis'];
        if ( empty( $setting ) ) return $info;

        foreach ( $setting as $api ) {
            // klog($api);
            if ( empty($api['name']) || empty($api['endpoint']) || empty($api['username']) ) continue;
            $info[] = $api;
        }
        // klog($info);
        return $info;
    }

    private function blogger_getUsersBlogs() {
        $re = rpc()->blogger_getUsersBlog( $_REQUEST['endpoint'], $_REQUEST['username'], $_REQUEST['password'] );

        wp_send_json($re);
    }

    private function blogPosting( $post_ID )
    {
        $apis = $this->parseBlogSetting();
        // klog("apis:"); klog($apis);

        if ( ! isset($_REQUEST['blogs']) ) return;
        $blogs = $_REQUEST['blogs'];

        $post = get_post( $post_ID );
        $desc = trim($post->post_content);

        $blogPost = [];
        $blogPost['title'] = $post->post_title;
        $desc = preg_replace("/\[caption[^\]]+\]/", '', $desc);
        $desc = str_replace("[/caption]", '', $desc);
        $desc = nl2br($desc);
        $blogPost['description'] = $desc;

        // @note if no content was input, then it just don't blogging.
        if ( ! empty( $blogPost['description'] ) ) {
            foreach ( $apis as $api ) {
                //
                if ( ! in_array( $api['name'], $blogs ) ) {
                    continue;
                }
                $blog_postID_key = "blog_postID_$api[name]";
                $blog_postID = get_post_meta( $post_ID, $blog_postID_key, true);
                if ( $blog_postID ) { // edit
                    $re = rpc()->metaWeblog_editPost($api['endpoint'], $api['username'], $api['password'], $blog_postID, $blogPost);
                    if ( ! $re ) {
                        klog("error on metaWeblog_editPost");
                    }
                }
                else { // new post
                    $postID = rpc()->metaWeblog_newPost( $api['endpoint'], $api['username'], $api['password'], $api['blogID'], $blogPost);
                    if ( empty($postID) ) {
                        klog("error on metaWeblog_newPost");
                    }
                    else {
                        delete_post_meta($post_ID, $blog_postID_key);
                        add_post_meta($post_ID, $blog_postID_key, $postID);
                    }
                }
            }
        }
    }

    /**
     *
     * Exits if the user has no right to edit/delete on the $post_id
     *
     * @Attention if the logged-in user is admin, then he can do 'edit/delete'
     *
     * @param $id
     * @param string $type
     *
     * @return bool
     */
    private function checkOwnership( $id, $type='post' )
    {
        if ( ! is_user_logged_in() ) wp_die("Please login");

        if ( current_user_can( 'manage_options' ) ) return true;

        $user = wp_get_current_user();
        $user_id = 0;
        if ( $user->exists() ) {
            if ( $type == 'post' ) {
                $post = get_post( $id );
                if ( empty($post) ) { // if post does not exists, it is a new post writing.
                    wp_die("Post does not exists");
                }
                $user_id = $post->post_author;
            }
            else if ( $type == 'comment' ) {
                $comment = get_comment( $id );
                if ( empty( $comment ) ) wp_die("Comment does not exists");
                $user_id = $comment->user_id;
            }
            else wp_die( 'Wrong Post Type Check');

            if ( $user->ID == $user_id ) {
                // ok
            }
            else {
                wp_die("You are not the owner of the $type");
            }
        }
        else {
            wp_die("User does not exists.");
        }
        return true;
    }


    /**
     *
     */
    private function login() {
        $credits = array(
            'user_login'    => in('user_login'),
            'user_password' => in('user_pass'),
            'rememberme'    => in('rememberme')
        );
        $re = wp_signon( $credits, false );
        if ( is_wp_error($re) ) {
            wp_die( $re->get_error_code() );
        }
        else {
            $url = isset($_REQUEST['return_uri']) ? $_REQUEST['return_uri'] : home_url();
            echo "
                <script>
                location.href='$url';
                </script>
            ";
            exit;
        }
    }

    private function checkLogin()
    {
        if ( ! is_user_logged_in() ) wp_die("Please login");
    }

    private function locateTemplate( $category_slug, $page )
    {
        if ( $page == 'commentEdit' ) {
            // di( the_title() );
            $comment = get_comment( $category_slug );
            $post = get_post( $comment->comment_post_ID );

            $category =current( get_the_category( $post->ID ) );

        }
        else {
            if ( is_numeric($category_slug) ) $category = current(get_the_category( $category_slug ));
            else $category = get_category_by_slug( $category_slug );
        }

        $postfix = get_term_meta($category->cat_ID, 'template', true);

        $template = "forum-{$page}-$postfix.php";
        $custom_template = get_stylesheet_directory() . DIRECTORY_SEPARATOR . $template;

        if ( file_exists($custom_template) ) return $template;
        else return "forum-{$page}-basic.php";
    }
}

function forum() {
    return new forum();
}
