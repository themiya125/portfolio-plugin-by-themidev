<?php
defined('ABSPATH') || exit;

/* =====================================================
 * REGISTER META BOXES
 * ===================================================== */

add_action('add_meta_boxes', function () {


   

    add_meta_box(
        'td_project_gallery',
        'Project Gallery',
        'td_project_gallery_box',
        'themidev_portfolio',
        'normal'
    );
  
    add_meta_box(
        'portfolio_featured',
        'Featured Project',
        'portfolio_featured_callback',
        'themidev_portfolio',
        'side'
    );


});

/* =====================================================
 * OVERVIEW META BOX
 * ===================================================== */



/* =====================================================
 * LINKS META BOX
 * ===================================================== */


/* =====================================================
 * GALLERY META BOX
 * ===================================================== */

function td_project_gallery_box( $post ) {

    $images = get_post_meta( $post->ID, '_td_gallery_images', true );
    $images = is_array( $images ) ? $images : [];

    for ( $i = 1; $i <= 5; $i++ ) :

        $img_id  = $images[$i] ?? '';
        $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
        ?>
        <div style="margin-bottom:12px;">
            <label><strong>Gallery Image <?php echo $i; ?></strong></label><br>

            <input type="hidden"
                   name="td_gallery_images[<?php echo $i; ?>]"
                   value="<?php echo esc_attr( $img_id ); ?>">

            <button type="button" class="button td-image-upload">Select Image</button>

            <?php if ( $img_url ) : ?>
                <div style="margin-top:6px;">
                    <img src="<?php echo esc_url( $img_url ); ?>" style="width:100px;border-radius:4px;">
                </div>
            <?php endif; ?>
        </div>
    <?php endfor;
}

/* =====================================================
 * SINGLE SAVE HANDLER (CRITICAL FIX)
 * ===================================================== */

add_action( 'save_post_themidev_portfolio', function ( $post_id ) {

    if (
        ! isset( $_POST['td_portfolio_nonce'] ) ||
        ! wp_verify_nonce( $_POST['td_portfolio_nonce'], 'td_portfolio_save_meta' )
    ) return;

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( wp_is_post_revision( $post_id ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    /* -------- Overview -------- */
    foreach ( ['client', 'role', 'timeline', 'project_type'] as $field ) {

        if ( isset( $_POST["td_$field"] ) ) {
            $value = sanitize_text_field( $_POST["td_$field"] );

            if ( $value === '' ) {
                delete_post_meta( $post_id, "_td_$field" );
            } else {
                update_post_meta( $post_id, "_td_$field", $value );
            }
        }
    }

    /* -------- Links -------- */
    foreach ( ['live_url', 'source_url'] as $field ) {

        if ( isset( $_POST["td_$field"] ) ) {
            $url = trim( $_POST["td_$field"] );

            if ( filter_var( $url, FILTER_VALIDATE_URL ) ) {
                update_post_meta( $post_id, "_td_$field", esc_url_raw( $url ) );
            } else {
                delete_post_meta( $post_id, "_td_$field" );
            }
        }
    }

    /* -------- Gallery -------- */
    if ( isset( $_POST['td_gallery_images'] ) && is_array( $_POST['td_gallery_images'] ) ) {
        $images = array_map( 'absint', $_POST['td_gallery_images'] );
        $images = array_filter( $images );
        update_post_meta( $post_id, '_td_gallery_images', $images );
    }
});

/* =====================================================
 * ADMIN MEDIA
 * ===================================================== */

add_action('admin_enqueue_scripts', function ( $hook ) {

    if ( ! in_array( $hook, ['post.php', 'post-new.php'], true ) ) return;

    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'themidev_portfolio' ) return;

    wp_enqueue_media();

    wp_enqueue_script(
        'td-gallery-js',
        THEMIDEV_PORTFOLIO_URL . 'assets/gallery.js',
        ['jquery'],
        '1.0',
        true
    );
});





function portfolio_featured_callback($post) {

    wp_nonce_field('td_portfolio_save_meta', 'td_portfolio_nonce');

    $value = get_post_meta($post->ID, '_is_featured', true);
    ?>
    <label style="display:block;margin-top:6px;">
        <input type="checkbox" name="is_featured" value="1" <?php checked($value, 1); ?> />
        Mark as Featured
    </label>
    <?php
}

add_action('save_post_themidev_portfolio', function ($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['is_featured'])) {
        update_post_meta($post_id, '_is_featured', 1);
    } else {
        delete_post_meta($post_id, '_is_featured');
    }
});
