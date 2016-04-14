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


/*
$catarr = array(
    'cat_name' => '11 번째 테스트 카테고리',
    'category_description' => "이것은 글 카테고리입니다.",
    'category_nicename' => 'test_category',
    'category_parent' => '',
    'taxonomy' => 'category' // 기본 값 생략가능.
);
$ID = wp_insert_category( $catarr, true );
if ( is_wp_error( $ID ) ) wp_die($ID->get_error_message());
else {
    $ID = wp_insert_category( ['cat_ID'=>$ID, 'cat_name'=>'카테고리 11', 'category_nicename'=>'category-11', 'category_description'=>'글 카테고리']);
    if ( is_wp_error( $ID ) ) wp_die($ID->get_error_message());
}
*/