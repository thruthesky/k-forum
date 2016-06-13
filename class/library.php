<?php

class library
{
    static $segments = [];
    public function __construct()
    {
    }


    /**
     *
     *
     * segment 는 0 부터 시작한다.
     *
     * @param null $n
     * @return array|null
     *      - $n 이 입력되었으면 $n 에 해당하는 부분의 segment 를 리턴한다.
     *      - 만약 $n 부분에 해당하는 segment 가 없으면 null 을 리턴한다.
     *      - $n 이 입력되지 않았으면 segments 전체를 배열로 리턴한다.
     */
    public function segments($n = NULL) {

        if ( empty( self::$segments ) ) {
            $u = strtolower(site_url());
            $u = str_replace("http://", '', $u);
            $u = str_replace("https://", '', $u);
            $r = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $uri = str_replace( "$u/", '', $r);
            $arr = explode('?', $uri);
            if ( $arr ) {
                self::$segments = explode('/', $arr[0]);
            }
        }

        if ( $n !== NULL ) {
            if ( isset(self::$segments[$n]) ) return self::$segments[$n];
            else return NULL;
        }
        else return self::$segments;
    }

    public function segment($n) {
        return $this->segments($n);
    }


    public function sanitize_special_chars($filename) {
        $pi = pathinfo($filename);
        $sanitized = md5($pi['filename'] . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . time());
        if ( isset($pi['extension']) && $pi['extension'] ) return $sanitized . '.' . $pi['extension'];
        else return $sanitized;
    }
    public function get_upload_error_message($code) {
        $errors = array(
            0 => 'There is no error, the file uploaded with success',
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            4 => 'No file was uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        return $errors[ $code ];
    }


    /**
     *
     * Returns WP_Term Objects of category in hierarchical tree by doing recursive calls.
     *
     * It adds 'depth' attributes on the Object.
     *
     * @param $cat_ID
     * @param int $depth
     * @return array
     *
     * @code
     *      di( lib()->get_categories_with_depth( get_category_by_slug( 'forum' )->term_id ) );
     * @endcode
     *
     * @code Display in select box.
     *
     * $cat = get_category_by_slug(FORUM_CATEGORY_SLUG);
    $categories = lib()->get_categories_with_depth( $cat->term_id );
    echo '<select>';
    foreach ( $categories as $category ) {
    $pads = str_repeat( '&nbsp;&nbsp;', $category->depth );
    echo "<option value='{$category->term_id}'>$pads{$category->name}</option>";
    }
    echo '</select>';
     * @endcode
     */
    public function get_categories_with_depth($cat_ID, $depth = 0 ) {
        static $output;
        if ( $depth == 0 ) $output = [];
        $depth ++;
        $categories = get_categories( ['parent' => $cat_ID, 'hide_empty'=>false] );
        if ( count($categories) > 0 ) {
            foreach ( $categories as $category ) {
                $category->depth = $depth - 1;
                $output[] = $category;
                $this->get_categories_with_depth( $category->term_id, $depth );
            }
        }
        return $output;
    }


    public function di($o) {
        $re = print_r($o, true);
        $re = str_replace(" ", "&nbsp;", $re);
        $re = explode("\n", $re);
        echo implode("<br>", $re);
    }


    /**
     *
     * @param $date_string
     * @return bool|string
     *
     * @code
     *      <?php echo lib()->date_short($recent["post_date"])?>
     * @endcode
     */
    public function date_short($date_string) {
        $time = strtotime( $date_string );
        $Ymd = date('Ymd');
        $post_Ymd = date('Ymd', $time);
        if ( $Ymd == $post_Ymd ) return date('h:i a', $time);
        else return date('Y-m-d', $time);
    }


} // EO Library

/**
 * @return library
 */
function lib() {
    return new library();
}



if ( ! function_exists('seg') ) {
    function seg($n) {
        return lib()->segment($n);
    }
}

if ( ! function_exists( 'di' ) ) {
    function di($o) {
        lib()->di($o);
    }
}


/**
 * Adds a line of message into /wp-content/debug.log
 * @note since error_log() works differently based on the settings, it adds message directly on debug.log
*
*/
    function klog( $message ) {
        static $count_klog = 0;
        if( WP_DEBUG === true && WP_DEBUG_LOG === true ) {
	}
	else return;


        $count_klog ++;
            if( is_array( $message ) || is_object( $message ) ){
                $message = print_r( $message, true );
            }
            else {

            }
        $message = "[klog $count_klog] $message\n";
	$file = ABSPATH . 'wp-content/debug.log';
	file_put_contents($file, $message, FILE_APPEND);
        //error_log( $message );
    }

