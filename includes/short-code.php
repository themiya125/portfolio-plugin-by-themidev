<?php
defined('ABSPATH') || exit;

add_shortcode('featured_projects', function () {

    // Enqueue styles & scripts
    wp_enqueue_style(
        'themidev-featured-projects',
        plugin_dir_url(__FILE__) . '../assets/plugin.css',
        [],
        '2.0'
    );

    wp_enqueue_script(
        'themidev-featured-projects',
        plugin_dir_url(__FILE__) . '../assets/plugin.js',
        ['jquery'],
        '2.0',
        true
    );

    // Get ALL categories
    $terms = get_terms([
        'taxonomy'   => 'portfolio_tech',
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC'
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return '<div class="fp-wrapper"><p style="text-align:center;color:#fff;">No categories found.</p></div>';
    }

    ob_start();
    ?>

    <style>
    /* ========================
       FEATURED CATEGORIES STYLES
       Desktop: Hover Overlay with Title & Description
       Mobile: Normal Card Layout
    ======================== */
    :root {
        --navy-dark: #0a0a2a;
        --navy-medium: #0f0f3a;
        --navy-light: #1a1a4a;
        --purple: #6c63ff;
        --purple-dark: #5a52d5;
        --purple-light: #8a82ff;
        --text-light: #ffffff;
        --text-gray: #b8b8d0;
        --card-bg: rgba(15, 15, 58, 0.95);
        --shadow-sm: 0 10px 30px rgba(0, 0, 0, 0.3);
        --shadow-md: 0 20px 40px rgba(0, 0, 0, 0.4);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fp-wrapper {
        margin: 2rem 0;
        padding: 1rem;
        background: transparent;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Grid Layout */
    .fp-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin: 2rem 0;
    }

    /* Card Base Styles */
    .fp-card {
        background: var(--card-bg);
        border-radius: 20px;
        overflow: hidden;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(108, 99, 255, 0.1);
        transition: var(--transition);
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
        position: relative;
        width: 100%;
    }

    .fp-card:hover {
        transform: translateY(-8px);
        border-color: rgba(108, 99, 255, 0.3);
        box-shadow: var(--shadow-md);
    }

    .fp-card a {
        text-decoration: none;
        display: block;
        position: relative;
    }

    /* Card Image */
    .card-image {
        position: relative;
        overflow: hidden;
        height: 260px;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fp-card:hover .card-image img {
        transform: scale(1.1);
    }

    /* Category Badge */
    .card-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(108, 99, 255, 0.9);
        backdrop-filter: blur(5px);
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 2;
        transition: var(--transition);
    }

    .fp-card:hover .card-category {
        background: rgba(108, 99, 255, 1);
        transform: translateY(-2px);
    }

    /* ========================
       DESKTOP STYLES (min-width: 992px)
       Hover Overlay Effect
    ======================== */
    @media (min-width: 992px) {
        /* Overlay - Hidden by default, shows on hover */
        .card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, hsla(243, 91%, 73%, 0.6), hsla(244, 61%, 58%, 0.6));
            backdrop-filter: blur(5px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 3;
        }

        .fp-card:hover .card-overlay {
            opacity: 1;
            transform: translateY(0);
        }

        .overlay-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            transform: translateY(20px);
            transition: transform 0.3s ease 0.1s;
        }

        .fp-card:hover .overlay-title {
            transform: translateY(0);
        }

        .overlay-description {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            transform: translateY(20px);
            transition: transform 0.3s ease 0.15s;
        }

        .fp-card:hover .overlay-description {
            transform: translateY(0);
        }

        .overlay-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            background: white;
            color: var(--purple);
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transform: translateY(20px);
            transition: all 0.3s ease 0.2s;
                    margin-bottom: 20px;
        }

        .fp-card:hover .overlay-btn {
            transform: translateY(0);
        }

        .overlay-btn:hover {
            background: var(--purple);
            color: white;
            gap: 0.75rem;
        }

        /* Hide normal content on desktop */
        .card-content {
            display: none;
        }
    }

    /* ========================
       MOBILE & TABLET STYLES (max-width: 991px)
       Normal Card Layout - Always Visible
    ======================== */
    @media (max-width: 991px) {
        .fp-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .card-image {
            height: 220px;
        }
        
        /* Hide overlay on mobile */
        .card-overlay {
            display: none;
        }
        
        /* Show normal content on mobile */
        .card-content {
            padding: 1.25rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.3;
            color: var(--text-light);
        }
        
        .card-description {
            color: var(--text-gray);
            line-height: 1.6;
            margin-bottom: 1rem;
            font-size: 0.85rem;
        }
        
        .read-more {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--purple);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: var(--transition);
            align-self: flex-start;
        }
        
        .read-more i {
            transition: transform 0.3s ease;
        }
        
        .read-more:hover {
            color: var(--purple-light);
            gap: 0.75rem;
        }
        
        .read-more:hover i {
            transform: translateX(5px);
        }
        
        .fp-card {
            display: flex;
            flex-direction: column;
            height: 100%;
                    min-height: 400px;
        }
        
        .fp-card a {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .card-image {
            flex-shrink: 0;
        }
        
        .card-content {
            flex: 1;
                    padding: 2rem 1.25rem;

        }
    }

    /* Small tablets (768px to 991px) */
    @media (min-width: 768px) and (max-width: 991px) {
        .card-image {
            height: 200px;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .card-description {
            font-size: 0.8rem;
        }
    }

    /* Mobile phones (max-width: 767px) */
    @media (max-width: 767px) {
        .fp-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
      
        .card-title {
            font-size: 1rem;
        }
        
        .card-description {
            font-size: 0.8rem;
        }
        
        .read-more {
            font-size: 0.8rem;
        }
    }

    /* Hidden items for show more */
    .fp-card.fp-hidden {
        display: none;
    }

    /* Button Container */
    .fp-buttons-container {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 3rem;
        flex-wrap: wrap;
    }

    /* Button Styles */
    .fp-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.85rem 2rem;
        background: linear-gradient(135deg, var(--purple), var(--purple-dark));
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        font-size: 1rem;
        min-width: 180px;
        justify-content: center;
    }

    .fp-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        background: linear-gradient(135deg, var(--purple-dark), var(--purple));
        color: white;
    }

    .fp-btn.see-more {
        background: linear-gradient(135deg, #2d3748, #1a202c);
    }

    .fp-btn.see-more:hover {
        background: linear-gradient(135deg, #1a202c, #2d3748);
    }

    .fp-btn.view-portfolio {
        background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    }

    /* Card hover effect overlay */
    .fp-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(108, 99, 255, 0.1), rgba(108, 99, 255, 0));
        border-radius: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 1;
    }

    .fp-card:hover::before {
        opacity: 1;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fp-card {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }

    .fp-card:nth-child(1) { animation-delay: 0.05s; }
    .fp-card:nth-child(2) { animation-delay: 0.1s; }
    .fp-card:nth-child(3) { animation-delay: 0.15s; }
    .fp-card:nth-child(4) { animation-delay: 0.2s; }
    .fp-card:nth-child(5) { animation-delay: 0.25s; }
    .fp-card:nth-child(6) { animation-delay: 0.3s; }

    @keyframes cardAppear {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .fp-card.fp-newly-shown {
        animation: cardAppear 0.5s ease forwards;
    }
    </style>

    <div class="fp-wrapper">
        <div class="fp-grid" id="fp-grid-container">
            <?php 
            $items_per_page = 6;
            $total_cards = count($terms);
            
            foreach ($terms as $index => $term):
                $hidden = $index >= $items_per_page ? 'fp-hidden' : '';
                
                // Get category image
                $image_url = '';
                $image_id = get_term_meta($term->term_id, 'category_image', true);
                
                if ($image_id) {
                    $image_url = wp_get_attachment_url($image_id);
                }
                
                // Fallback to first project image
                if (!$image_url) {
                    $args = [
                        'post_type' => 'themidev_portfolio',
                        'tax_query' => [
                            [
                                'taxonomy' => 'portfolio_tech',
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ]
                        ],
                        'posts_per_page' => 1,
                        'fields' => 'ids'
                    ];
                    $project_ids = get_posts($args);
                    if (!empty($project_ids)) {
                        $featured_image = get_post_thumbnail_id($project_ids[0]);
                        if ($featured_image) {
                            $image_url = wp_get_attachment_url($featured_image);
                        }
                    }
                }
                
                // Placeholder if no image
                if (!$image_url) {
                    $image_url = 'https://via.placeholder.com/600x400/1a1a4a/6c63ff?text=' . urlencode($term->name);
                }
                
                $link = add_query_arg(
                    'tech',
                    $term->slug,
                    get_post_type_archive_link('themidev_portfolio')
                );
                
                $project_count = $term->count;
                $description = wp_trim_words($term->description, 20, '...');
                if (empty($description)) {
                    $description = "Explore {$term->name} projects and discover innovative solutions.";
                }
            ?>
                <div class="fp-card <?php echo esc_attr($hidden); ?>" data-category-id="<?php echo esc_attr($term->term_id); ?>">
                    <a href="<?php echo esc_url($link); ?>">
                        <div class="card-image">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($term->name); ?>" loading="lazy">
                            <div class="card-category">
                                <i class="fas fa-folder"></i> 
                                <?php echo $project_count; ?> <?php echo $project_count == 1 ? 'Project' : 'Projects'; ?>
                            </div>
                            
                            <!-- Desktop Hover Overlay -->
                            <div class="card-overlay">
                                <h3 class="overlay-title"><?php echo esc_html($term->name); ?></h3>
                                <p class="overlay-description"><?php echo esc_html($description); ?></p>
                                <span class="overlay-btn">
                                    Browse Projects <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Mobile Content (visible only on mobile) -->
                        <div class="card-content">
                            <h3 class="card-title"><?php echo esc_html($term->name); ?></h3>
                            <p class="card-description"><?php echo esc_html($description); ?></p>
                            <span class="read-more">
                                Browse Category <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="fp-buttons-container">
            <?php if ($total_cards > $items_per_page): ?>
                <button id="fp-see-more" class="fp-btn see-more">
                    <i class="fas fa-eye"></i> See More (<span class="remaining-count"><?php echo $total_cards - $items_per_page; ?></span>)
                </button>
            <?php endif; ?>
            
            <a href="<?php echo get_post_type_archive_link('themidev_portfolio'); ?>" class="fp-btn view-portfolio">
                <i class="fas fa-th-large"></i> View All Projects
            </a>
        </div>
    </div>

    <script>
    (function($){
        'use strict';
        
        let currentCount = <?php echo $items_per_page; ?>;
        const totalCount = <?php echo $total_cards; ?>;
        const itemsToLoad = 3;
        
        $('#fp-see-more').on('click', function(e){
            e.preventDefault();
            const $button = $(this);
            const $hiddenCards = $('.fp-card.fp-hidden');
            
            if ($hiddenCards.length === 0) {
                $button.fadeOut(300);
                return;
            }
            
            const $cardsToShow = $hiddenCards.slice(0, itemsToLoad);
            
            $cardsToShow.each(function(index) {
                const $card = $(this);
                setTimeout(function() {
                    $card.slideDown(400, function() {
                        $(this).removeClass('fp-hidden');
                        $(this).addClass('fp-newly-shown');
                        
                        setTimeout(function() {
                            $card.removeClass('fp-newly-shown');
                        }, 500);
                    });
                }, index * 150);
            });
            
            currentCount += $cardsToShow.length;
            const remainingCount = totalCount - currentCount;
            
            if (remainingCount <= 0) {
                $button.fadeOut(300);
            } else {
                $button.find('.remaining-count').text(remainingCount);
            }
        });
    })(jQuery);
    </script>

    <?php
    return ob_get_clean();
});