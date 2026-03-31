<?php
defined('ABSPATH') || exit;

// Register taxonomy
add_action('init', function () {
    register_taxonomy('portfolio_tech', 'themidev_portfolio', [
        'label' => 'Categories',
        'public' => true,
        'hierarchical' => true,
        'show_in_rest' => true,

        // ✅ UPDATED SLUG
        'rewrite' => [
            'slug' => 'project-category',
            'with_front' => false
        ],

        'show_admin_column' => true,
        'description' => 'Portfolio categories with name, image and description',
    ]);
});


// ================= IMAGE FIELD =================

// Add field (add form)
add_action('portfolio_tech_add_form_fields', function () {
    ?>
    <div class="form-field term-group">
        <label>Category Image</label>
        <input type="button" class="button button-secondary" id="category-image-button" value="Upload/Add Image" />
        <input type="hidden" id="category-image-id" name="category-image-id" value="">
        <div id="category-image-wrapper"></div>
    </div>
    <?php
});

// Edit form
add_action('portfolio_tech_edit_form_fields', function ($term) {

    $image_id = get_term_meta($term->term_id, 'category_image', true);
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label>Category Image</label>
        </th>
        <td>
            <input type="button" class="button button-secondary" id="category-image-button" value="Upload/Add Image" />
            <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo esc_attr($image_id); ?>">

            <div id="category-image-wrapper">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width:100px;">
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php
});

// Save image
add_action('created_portfolio_tech', 'themidev_save_category_image', 10, 2);
add_action('edited_portfolio_tech', 'themidev_save_category_image', 10, 2);

function themidev_save_category_image($term_id)
{
    if (isset($_POST['category-image-id']) && $_POST['category-image-id'] !== '') {
        update_term_meta($term_id, 'category_image', intval($_POST['category-image-id']));
    } else {
        delete_term_meta($term_id, 'category_image');
    }
}

// Load media uploader
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'edit-tags.php' || $hook === 'term.php') {
        wp_enqueue_media();
        wp_enqueue_script(
            'themidev-category-image',
            plugin_dir_url(__FILE__) . '../assets/category-image.js',
            ['jquery'],
            '1.0',
            true
        );
    }
});