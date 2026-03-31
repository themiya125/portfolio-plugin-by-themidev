<?php
defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'td_portfolio_schema', 20 );

function td_portfolio_schema() {

    if ( ! is_singular( 'themidev_portfolio' ) ) {
        return;
    }

    global $post;

    if ( ! $post ) {
        return;
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type'    => 'CreativeWork',
        '@id'      => get_permalink( $post ) . '#creativework',
        'name'     => get_the_title( $post ),
        'url'      => get_permalink( $post ),
        'description' => wp_strip_all_tags(
            has_excerpt( $post )
                ? get_the_excerpt( $post )
                : wp_trim_words( $post->post_content, 25 )
        ),
        'author'   => [
            '@type' => 'Person',
            'name'  => get_the_author_meta( 'display_name', $post->post_author ),
        ],
        'datePublished' => get_the_date( 'c', $post ),
        'dateModified'  => get_the_modified_date( 'c', $post ),
    ];

    // Featured image
    if ( has_post_thumbnail( $post ) ) {
        $schema['image'] = get_the_post_thumbnail_url( $post, 'full' );
    }

    // Project links
    $same_as = [];

    $live = get_post_meta( $post->ID, '_td_live_url', true );
    if ( $live ) {
        $same_as[] = esc_url( $live );
    }

    $code = get_post_meta( $post->ID, '_td_source_url', true );
    if ( $code ) {
        $same_as[] = esc_url( $code );
    }

    if ( ! empty( $same_as ) ) {
        $schema['sameAs'] = $same_as;
    }

    echo '<script type="application/ld+json">';
    echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    echo '</script>';
}
