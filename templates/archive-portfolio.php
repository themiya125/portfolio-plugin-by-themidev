<?php
defined('ABSPATH') || exit;

get_header();

// Get selected category slug (if user clicked a category)
$selected_slug = isset($_GET['tech']) ? sanitize_text_field($_GET['tech']) : '';
$search_query = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$items_per_page = 9;
?>

<section class="portfolio-archive container">

<?php
// ==============================
// NO CATEGORY SELECTED → Show Categories
// ==============================
if (!$selected_slug):
    $terms = get_terms([
        'taxonomy' => 'portfolio_tech',
        'hide_empty' => true
    ]);

    if ($terms && !is_wp_error($terms)): ?>
        <h1 class="portfolio-page-title">All Project Categories</h1>
        <div class="fp-wrapper">
            <div class="fp-grid">
                <?php foreach ($terms as $term):
                    $image_id = get_term_meta($term->term_id, 'category_image', true);
                    $thumb = $image_id ? wp_get_attachment_url($image_id) : 'https://via.placeholder.com/600x400';
                    $link = add_query_arg('tech', $term->slug, get_post_type_archive_link('themidev_portfolio'));
                    $project_count = $term->count;
                ?>
                    <div class="fp-card modern-card category-card">
                        <div class="card-image">
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($term->name); ?>" loading="lazy">
                            <div class="card-category">
                                <i class="fas fa-folder"></i> <?php echo $project_count; ?> <?php echo $project_count == 1 ? 'Project' : 'Projects'; ?>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3 class="card-title">
                                <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($term->name); ?></a>
                            </h3>
                            <p class="card-description"><?php echo esc_html(wp_trim_words($term->description, 20)); ?></p>
                            <a href="<?php echo esc_url($link); ?>" class="read-more">
                                Browse Category <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="no-results">
            <i class="fas fa-folder-open"></i>
            <h3>No Project Categories Found</h3>
            <p>Please create some project categories and add projects to them.</p>
            <?php if (current_user_can('manage_options')): ?>
                <a href="<?php echo admin_url('edit-tags.php?taxonomy=portfolio_tech&post_type=themidev_portfolio'); ?>" class="back-button">
                    <i class="fas fa-plus"></i> Create Categories
                </a>
            <?php endif; ?>
        </div>
    <?php endif;

// ==============================
// CATEGORY SELECTED → Show Projects
// ==============================
else:
    $term = get_term_by('slug', $selected_slug, 'portfolio_tech');

    if ($term && !is_wp_error($term)):
        // Build query args
        $args = [
            'post_type' => 'themidev_portfolio',
            'tax_query' => [
                [
                    'taxonomy' => 'portfolio_tech',
                    'field'    => 'slug',
                    'terms'    => $selected_slug,
                ]
            ],
            'posts_per_page' => $items_per_page,
            'paged' => $current_page,
            'post_status' => 'publish'
        ];
        
        // Add search filter
        if (!empty($search_query)) {
            $args['s'] = $search_query;
        }
        
        $query = new WP_Query($args);
        $total_posts = $query->found_posts;
        $total_pages = $query->max_num_pages;

        if ($query->have_posts()): ?>
            <h1 class="portfolio-page-title"><?php echo esc_html($term->name); ?> Projects</h1>
            
            <!-- Search Bar -->
            <div class="portfolio-search">
                <form method="get" action="" class="search-form">
                    <input type="hidden" name="tech" value="<?php echo esc_attr($selected_slug); ?>">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" name="search" placeholder="Search <?php echo esc_attr($term->name); ?> projects..." value="<?php echo esc_attr($search_query); ?>" class="search-input">
                        <?php if (!empty($search_query)): ?>
                            <a href="<?php echo esc_url(remove_query_arg('search')); ?>" class="search-clear">
                                <i class="fas fa-times"></i>
                            </a>
                        <?php endif; ?>
                        <button type="submit" class="search-btn">Search</button>
                    </div>
                </form>
            </div>
            
            <div style="text-align:center; margin-bottom:2rem;">
                <a href="<?php echo get_post_type_archive_link('themidev_portfolio'); ?>" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to All Categories
                </a>
            </div>

            <!-- Results Count -->
            <div class="results-count">
                <i class="fas fa-list"></i> Showing <?php echo $query->post_count; ?> of <?php echo $total_posts; ?> projects
            </div>

            <div class="fp-wrapper">
                <div class="fp-grid">
                    <?php while ($query->have_posts()): $query->the_post();
                        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium_large') ?: 'https://via.placeholder.com/600x400';
                        $link = get_permalink();
                        $excerpt = get_the_excerpt();
                        $title = get_the_title();
                    ?>
                        <div class="fp-card modern-card">
                            <div class="card-image">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
                                <div class="card-category">
                                    <i class="fas fa-folder"></i> <?php echo esc_html($term->name); ?>
                                </div>
                            </div>
                            <div class="card-content">
                                <h3 class="card-title">
                                    <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
                                </h3>
                                <p class="card-description"><?php echo wp_trim_words($excerpt, 20); ?></p>
                                <a href="<?php echo esc_url($link); ?>" class="read-more">
                                    Read More <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <div class="pagination-wrapper">
                            <?php
                            echo paginate_links([
                                'base'      => add_query_arg('paged', '%#%'),
                                'format'    => '',
                                'prev_text' => '<i class="fas fa-chevron-left"></i> Previous',
                                'next_text' => 'Next <i class="fas fa-chevron-right"></i>',
                                'total'     => $total_pages,
                                'current'   => $current_page,
                                'mid_size'  => 2,
                                'end_size'  => 1,
                            ]);
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h3>No projects found</h3>
                <p>No projects found in "<?php echo esc_html($term->name); ?>" <?php echo $search_query ? 'matching "' . esc_html($search_query) . '"' : ''; ?>.</p>
                <?php if ($search_query): ?>
                    <a href="<?php echo esc_url(remove_query_arg('search')); ?>" class="back-button">
                        <i class="fas fa-eye"></i> View All Projects in <?php echo esc_html($term->name); ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo get_post_type_archive_link('themidev_portfolio'); ?>" class="back-button">
                        <i class="fas fa-folder"></i> Browse Other Categories
                    </a>
                <?php endif; ?>
            </div>
        <?php endif;
    else:
        echo '<div class="no-results">
                <i class="fas fa-exclamation-triangle"></i>
                <h3>Invalid Category</h3>
                <p>The category you\'re looking for doesn\'t exist.</p>
                <a href="' . get_post_type_archive_link('themidev_portfolio') . '" class="back-button">
                    <i class="fas fa-arrow-left"></i> Back to Categories
                </a>
              </div>';
    endif;
endif;
?>

</section>

<?php get_footer(); ?>

<style>
/* ========================
   MODERN PORTFOLIO STYLES
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

body {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy-medium) 100%);
    min-height: 100vh;
}

.portfolio-archive {
    max-width: 1280px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
    min-height: 100vh;
    margin-top: 50px;
}

/* Page Title */
.portfolio-page-title {
    font-size: 3rem;
    text-align: center;
    margin: 1rem 0 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--text-light) 0%, var(--purple-light) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.02em;
    animation: fadeInUp 0.6s ease;
        margin-top: 50px;
}

/* Results Count */
.results-count {
    text-align: center;
    color: var(--text-gray);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* Back Button */
.back-button {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    color: #fff;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    box-shadow: var(--shadow-sm);
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    background: linear-gradient(135deg, var(--purple-dark), var(--purple));
    color: #fff;
}

/* Search Bar */
.portfolio-search {
    max-width: 600px;
    margin: 0 auto 2rem;
}

.search-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    background: var(--card-bg);
    border-radius: 60px;
    padding: 0.25rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(108, 99, 255, 0.2);
    transition: var(--transition);
}

.search-wrapper:focus-within {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.1);
}

.search-icon {
    position: absolute;
    left: 20px;
    color: var(--purple);
    font-size: 1.1rem;
}

.search-input {
    flex: 1;
    padding: 1rem 1rem 1rem 3rem;
    background: transparent;
    border: none;
    color: var(--text-light);
    font-size: 1rem;
    outline: none;
}

.search-input::placeholder {
    color: var(--text-gray);
}

.search-clear {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-gray);
    transition: var(--transition);
    margin-right: 0.5rem;
}

.search-clear:hover {
    background: rgba(255, 255, 255, 0.2);
    color: var(--text-light);
}

.search-btn {
    padding: 0.75rem 1.75rem;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    border: none;
    border-radius: 60px;
    color: white;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
}

.search-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(108, 99, 255, 0.4);
}

/* Grid Layout */
.fp-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    margin: 2rem 0;
}

/* Modern Card Style (Unified for both categories and projects) */
.modern-card {
    background: var(--card-bg);
    border-radius: 20px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(108, 99, 255, 0.1);
    transition: var(--transition);
    animation: fadeInUp 0.6s ease;
    animation-fill-mode: both;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.modern-card:hover {
    transform: translateY(-8px);
    border-color: rgba(108, 99, 255, 0.3);
    box-shadow: var(--shadow-md);
}

/* Card Image */
.card-image {
    position: relative;
    overflow: hidden;
    height: 240px;
    flex-shrink: 0;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.modern-card:hover .card-image img {
    transform: scale(1.1);
}

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
    z-index: 1;
    transition: var(--transition);
}

.modern-card:hover .card-category {
    background: rgba(108, 99, 255, 1);
    transform: translateY(-2px);
}

/* Card Content */
.card-content {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.card-title {
    font-size: 1.35rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.card-title a {
    color: var(--text-light);
    text-decoration: none;
    transition: var(--transition);
    background: linear-gradient(135deg, var(--text-light), var(--purple-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: inline-block;
}

.card-title a:hover {
    background: linear-gradient(135deg, var(--purple-light), var(--text-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    transform: translateX(5px);
}

.card-description {
    color: var(--text-gray);
    line-height: 1.6;
    margin-bottom: 1.25rem;
    font-size: 0.9rem;
    flex: 1;
}

.read-more {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--purple);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--transition);
    margin-top: auto;
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

/* Category Card Specific */
.category-card .card-category {
    background: rgba(255, 107, 107, 0.9);
}

.category-card:hover .card-category {
    background: rgba(255, 107, 107, 1);
}

/* Pagination */
.pagination {
    margin-top: 3rem;
    text-align: center;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    padding: 0 0.75rem;
    background: var(--card-bg);
    border: 1px solid rgba(108, 99, 255, 0.2);
    border-radius: 10px;
    color: var(--text-light);
    text-decoration: none;
    transition: var(--transition);
    font-weight: 500;
}

.pagination .page-numbers.current {
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    border-color: var(--purple);
    color: white;
}

.pagination .page-numbers:hover:not(.current) {
    transform: translateY(-2px);
    border-color: var(--purple);
    background: rgba(108, 99, 255, 0.1);
}

.pagination .prev,
.pagination .next {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.pagination .dots {
    background: transparent;
    border-color: transparent;
}

/* No Results */
.no-results {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--card-bg);
    border-radius: 20px;
    backdrop-filter: blur(10px);
    margin: 2rem 0;
}

.no-results i {
    font-size: 4rem;
    color: var(--purple);
    margin-bottom: 1rem;
}

.no-results h3 {
    color: var(--text-light);
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

.no-results p {
    color: var(--text-gray);
    margin-bottom: 1.5rem;
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

/* Stagger animation for cards */
.modern-card:nth-child(1) { animation-delay: 0.05s; }
.modern-card:nth-child(2) { animation-delay: 0.1s; }
.modern-card:nth-child(3) { animation-delay: 0.15s; }
.modern-card:nth-child(4) { animation-delay: 0.2s; }
.modern-card:nth-child(5) { animation-delay: 0.25s; }
.modern-card:nth-child(6) { animation-delay: 0.3s; }
.modern-card:nth-child(7) { animation-delay: 0.35s; }
.modern-card:nth-child(8) { animation-delay: 0.4s; }
.modern-card:nth-child(9) { animation-delay: 0.45s; }

/* Responsive Design */
@media (max-width: 1024px) {
    .fp-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .portfolio-page-title {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .portfolio-archive {
        padding: 1rem;
    }
    
    .fp-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .portfolio-page-title {
        font-size: 2rem;
    }
    
    .search-wrapper {
        flex-direction: column;
        border-radius: 20px;
        background: var(--card-bg);
        padding: 1rem;
    }
    
    .search-icon {
        left: 20px;
        top: 18px;
    }
    
    .search-input {
        width: 100%;
        padding: 0.75rem 0.75rem 0.75rem 2.5rem;
        margin-bottom: 0.75rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50px;
    }
    
    .search-clear {
        position: absolute;
        right: 20px;
        top: 18px;
    }
    
    .search-btn {
        width: 100%;
        padding: 0.75rem;
    }
    
    .pagination .page-numbers {
        min-width: 35px;
        height: 35px;
        font-size: 0.85rem;
    }
    
    .pagination .prev,
    .pagination .next {
        padding: 0 0.5rem;
    }
    
    .card-image {
        height: 200px;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 10px;
}

::-webkit-scrollbar-track {
    background: var(--navy-dark);
}

::-webkit-scrollbar-thumb {
    background: var(--purple);
    border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--purple-dark);
}

/* Loading Animation */
@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

/* Hover Effects for Cards */
.modern-card {
    position: relative;
}

.modern-card::before {
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

.modern-card:hover::before {
    opacity: 1;
}
</style>