<?php
defined('ABSPATH') || exit;

add_action('init', 'themidev_portfolio_register_cpt');

function themidev_portfolio_register_cpt()
{
    register_post_type('themidev_portfolio', [
        'labels' => [
            'name'          => 'Projects',
            'singular_name' => 'Project',
            'menu_name'     => 'Projects', 
        ],
        'public'        => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'  => true,

        // ✅ FORCE PROJECTS SLUG
        'rewrite' => [
            'slug' => 'projects',
            'with_front' => false
        ],

        // ✅ EXPLICIT ARCHIVE SLUG (VERY IMPORTANT)
        'has_archive' => 'projects',

        'publicly_queryable' => true,
        'query_var' => true,
    ]);
}