<?php
/**
 * Plugin Name: K Forum
 * Plugin URI: http://it.philgo.com
 * Author: JaeHo Song
 * Description: This is K Forum.
 * Version: 0.0.4
 *
 *
 *
 */
if ( ! defined('ABSPATH') ) exit;
define( 'K_FORUM', true);
define( 'FORUM_FILE_PATH', __FILE__ );
define( 'FORUM_PATH', plugin_dir_path( __FILE__ ) );
define( 'FORUM_URL',  plugin_dir_url( __FILE__ ) );
define( 'FORUM_CATEGORY_SLUG',  'forum' );
define( 'FORUM_FILE_WITH_NO_POST',  90000010 );
define( 'FORUM_COMMENT_POST_NUMBER', 100000000 );

require_once "class/library.php";
require_once "class/forum.php";
require_once "class/post.php";
require_once "class/rpc.php";

//klog('begin');


forum()
    ->init()
        ->addHooks()
        ->addAdminMenu()
        ->manageRoles()
        ->addFilters()
    ->loadText()
    ->enqueue();


register_activation_hook( __FILE__, function() {

    klog("register_activation_hook()");

    forum()->flushRewrites();

});

register_deactivation_hook( __FILE__, function() {

    klog('register_decativation_hook()');

});


add_action( 'admin_init', function() {
    register_setting( 'k_forum', 'k_forum' );
});



if ( isset( $_REQUEST['test'] ) && $_REQUEST['test'] == 'k-forum' ) {
    include 'test.php';
    exit;
}
