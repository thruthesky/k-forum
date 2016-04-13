<?php
/**
 * Plugin Name: Korean Style Forum
 * Plugin URI: http://it.philgo.com
 * Author: JaeHo Song
 * Description: This is Korean Style Forum.
 * Version: 0.0.4
 *
 *
 *
 */
if ( ! defined('ABSPATH') ) exit;
define( 'FORUM_PATH', plugin_dir_path( __FILE__ ) );
define( 'FORUM_URL',  plugin_dir_url( __FILE__ ) );
define( 'FORUM_CATEGORY_SLUG',  'forum' );

require_once "class/library.php";
require_once "class/forum.php";
require_once "class/post.php";

$forum = forum();
$forum->init();
$forum->enqueue();

