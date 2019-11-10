<?php
///**
// *  Copyright (c) 2019 Herborn Software
// *
// *  @package kar
// */
//
//
//defined('ABSPATH') or die("Thanks for visting");
//
//if (!class_exists('KAR_Test_Post_Type')) {
//
//    /**
//     * Class KAR_Test_Post_Type https://1fix.io/blog/2016/02/05/parent-from-another-cpt/
//     */
//    final class KAR_Test_Post_Type {
//
//        private $post_type = 'lesson';
//        private $parent_type = 'course';
//
//        public static function install() {
//            return new KAR_Test_Post_Type();
//        }
//
//        private function __construct()
//        {
//            //add_action('init', array($this, 'register_type'));
//            //add_action('init', array($this, 'register_parent'));
//            //add_action('add_meta_boxes', array($this, 'my_add_meta_boxes'));
//
//            //add_rewrite_tag('%lesson%', '([^/]+)', 'lesson=');
//
//
//            //add_permastruct('lesson', '/lesson/%course%/%lesson%', false);
//            //add_rewrite_rule('^lesson/([^/]+)/([^/]+)/?','index.php?lesson=$matches[2]','top');
//            //add_action('post_type_link', array($this, 'my_permalinks'));
//        }
//
//        function register_type() {
//            $labels = array(
//                'name'                  => _x( 'Courses', 'Post Type General Name', 'kar' ),
//                'singular_name'         => _x( 'Course', 'Post Type Singular Name', 'kar' ),
//                'menu_name'             => __( 'Courses', 'kar' ),
//                'name_admin_bar'        => __( 'Courses', 'kar' )
//            );
//            $args = array(
//                'label'                 => __( 'Course', 'kar' ),
//                'labels'                => $labels,
//                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
//                'hierarchical'          => true,
//                'public'                => true,
//                'show_in_rest' => true,
//            );
//            register_post_type( 'course', $args );
//        }
//
//        function register_parent() {
//            $labels = array(
//                'name'                  => _x( 'Lessons', 'Post Type General Name', 'kar' ),
//                'singular_name'         => _x( 'Lesson', 'Post Type Singular Name', 'kar' ),
//                'menu_name'             => __( 'Lessons', 'kar' ),
//                'name_admin_bar'        => __( 'Lessons', 'kar' )
//            );
//            $args = array(
//                'label'                 => __( 'Lesson', 'kar' ),
//                'labels'                => $labels,
//                'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments'),
//                'hierarchical'          => false,
//                'public'                => true,
//                'show_in_rest' => true,
//            );
//            register_post_type( 'lesson', $args );
//        }
//
//        function my_add_meta_boxes() {
//            add_meta_box( 'lesson-parent', 'Course', array($this, 'lesson_attributes_meta_box'), 'lesson', 'side', 'high' );
//        }
//
//        function lesson_attributes_meta_box( $post ) {
//            $post_type_object = get_post_type_object( $post->post_type );
//            $pages = wp_dropdown_pages( array( 'post_type' => 'course', 'selected' => $post->post_parent, 'name' => 'parent_id', 'show_option_none' => __( '(no parent)' ), 'sort_column'=> 'menu_order, post_title', 'echo' => 0 ) );
//            if ( ! empty( $pages ) ) {
//                echo $pages;
//            }
//        }
//
//        function my_permalinks($permalink, $post, $leavename) {
//            $post_id = $post->ID;
//            if($post->post_type != 'lesson' || empty($permalink) || in_array($post->post_status, array('draft', 'pending', 'auto-draft')))
//                return $permalink;
//            $parent = $post->post_parent;
//            $parent_post = get_post( $parent );
//            $permalink = str_replace('%course%', $parent_post->post_name, $permalink);
//            return $permalink;
//        }
//
//    }
//}
//
//
