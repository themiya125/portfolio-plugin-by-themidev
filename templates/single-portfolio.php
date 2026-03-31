<?php
defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="blog-hero">
  <div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
  </div>

  <div class="blog-hero-inner container">
    <h1 class="blog-title">Projects</h1>
    <div class="breadcrumb">
      <?php
      if(function_exists('yoast_breadcrumb')){
          yoast_breadcrumb('<span id="breadcrumbs">', '</span>');
      }
      ?>
    </div>
  </div>
</section>

<div class="portfolio-single">
<?php while ( have_posts() ) : the_post(); ?>

<!-- HEADER -->
<section class="portfolio-header container">
    <div class="portfolio-header-content">
        <h1 class="portfolio-title"><?php the_title(); ?></h1>
        
        <?php if ( has_excerpt() ) : ?>
            <div class="portfolio-excerpt">
                <?php the_excerpt(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- HERO IMAGE -->
<?php if ( has_post_thumbnail() ) : ?>
<section class="portfolio-hero container">
    <div class="hero-container">
        <?php the_post_thumbnail( 'full', array('class' => 'hero-image') ); ?>
        <div class="hero-badge">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M7 7h10v10M7 17L17 7"/>
            </svg>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TECH STACK SECTION -->
<?php 
$tech_terms = get_the_terms( get_the_ID(), 'portfolio_tech' );
if ( $tech_terms && ! is_wp_error( $tech_terms ) ) : 
?>

<?php endif; ?>

<!-- CONTENT + SIDEBAR -->
<section class="portfolio-body container">
    <div class="content">
        <div class="content-card">
            <?php the_content(); ?>
        </div>
        
        <!-- GALLERY (ACF Safe) -->
        <?php
        $images = get_post_meta( get_the_ID(), '_td_gallery_images', true );
        if ( is_array( $images ) && ! empty( $images ) ) :
            $images = array_values( array_filter( $images ) );
            $count  = 1;
        ?>
        <section class="project-gallery">
            <div class="gallery-header">
                <h3><i class="fas fa-images"></i> Project Gallery</h3>
                <p>Visual showcase of the project</p>
            </div>
            <div class="gallery-grid-modern">
                <?php foreach ( $images as $img_id ) :
                    $full  = wp_get_attachment_image_url( $img_id, 'full' );
                    $thumb = wp_get_attachment_image( $img_id, 'large', false, [
                        'loading' => 'lazy',
                        'class' => 'gallery-thumb'
                    ] );
                    ?>
                    <div class="gallery-item-modern gallery-item-<?php echo $count; ?>">
                        <a href="<?php echo esc_url( $full ); ?>" data-fancybox="project-gallery" class="gallery-link">
                            <?php echo $thumb; ?>
                            <div class="gallery-overlay-modern">
                                <i class="fas fa-search-plus"></i>
                                <span>View Full Size</span>
                            </div>
                        </a>
                    </div>
                    <?php $count++; ?>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <aside class="sidebar">
        <!-- PROJECT INFO CARD -->
        <div class="info-card">
            <h3><i class="fas fa-info-circle"></i> Project Info</h3>
            <div class="info-list">
                <?php if ( get_post_meta( get_the_ID(), 'project_date', true ) ) : ?>
               <!-- <div class="info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <strong>Date</strong>
                        <span><?php echo esc_html( get_post_meta( get_the_ID(), 'project_date', true ) ); ?></span>
                    </div>
                </div>-->
                <?php endif; ?>
              <section class="tech-stack-showcase container">
 
    <div class="tech-stack-grid">
        <?php foreach ( $tech_terms as $tech ) : 
            $tech_icon = get_term_meta( $tech->term_id, 'tech_icon', true );
            $category_link = add_query_arg('tech', $tech->slug, get_post_type_archive_link('themidev_portfolio'));
        ?>
            <a href="<?php echo esc_url($category_link); ?>" class="tech-item-link">
                <div class="tech-item">
                    <?php if ( $tech_icon ) : ?>
                        <div class="tech-icon"><?php echo wp_kses_post( $tech_icon ); ?></div>
                    <?php else : ?>
                        <div class="tech-icon"><i class="fas fa-cube"></i></div>
                    <?php endif; ?>
                    <div class="tech-info">
                        <h4><?php echo esc_html( $tech->name ); ?></h4>
                        <span class="tech-count">
                            <i class="fas fa-folder-open"></i> <?php echo $tech->count; ?> <?php echo $tech->count == 1 ? 'project' : 'projects'; ?>
                        </span>
                    </div>
                    <div class="tech-arrow">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
            </div>
        </div>

        <!-- AUTHOR BOX -->
        <?php
        if ( class_exists( 'Kirki' ) ) :
            $author_image    = get_theme_mod( 'author_image' );
            $author_name     = get_theme_mod( 'author_name' );
            $author_position = get_theme_mod( 'author_position' );
            $author_bio      = get_theme_mod( 'author_bio' );

            if ( $author_image || $author_name || $author_position || $author_bio ) :
        ?>
            <div class="author-box-modern">
                <div class="author-header-modern">
                    <?php if ( $author_image ) : ?>
                        <div class="author-image-modern">
                            <img src="<?php echo esc_url( wp_get_attachment_url( $author_image ) ); ?>"
                                 alt="<?php echo esc_attr( $author_name ); ?>">
                        </div>
                    <?php endif; ?>
                    <div class="author-info-modern">
                        <?php if ( $author_name ) : ?>
                            <h3><?php echo esc_html( $author_name ); ?></h3>
                        <?php endif; ?>
                        <?php if ( $author_position ) : ?>
                            <p><?php echo esc_html( $author_position ); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ( $author_bio ) : ?>
                    <p class="author-bio-modern"><?php echo esc_html( $author_bio ); ?></p>
                <?php endif; ?>
                <div class="author-contact">
                    <a href="mailto:<?php echo esc_attr( get_theme_mod( 'author_email', '' ) ); ?>" class="contact-btn">
                        <i class="fas fa-envelope"></i> Contact Author
                    </a>
                </div>
            </div>
        <?php
            endif;
        endif;
        ?>

      
        <!-- <div class="share-card">
            <h3><i class="fas fa-share-alt"></i> Share This Project</h3>
            <div class="share-buttons">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" target="_blank" class="share-btn facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" target="_blank" class="share-btn twitter">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>" target="_blank" class="share-btn linkedin">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="mailto:?subject=<?php echo urlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>" class="share-btn email">
                    <i class="fas fa-envelope"></i>
                </a>
            </div>
        </div>-->
    </aside>
</section>

<?php endwhile; wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>

<style>
/* ========================
   MODERN PORTFOLIO SINGLE STYLES
   Matching Archive Page Design
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

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Blog Header */
.blog-hero {
    position: relative;
    padding: 120px 0 40px;
    background: linear-gradient(135deg, var(--purple-dark) 0%, var(--navy-dark) 100%);
    margin-bottom: 0;
    text-align: center;
    color: var(--text-light);
    overflow: hidden;
}

.blog-title {
    font-size: 3.75rem;
    font-weight: 800;
    margin: 1rem 0;
    background: linear-gradient(135deg, var(--text-light), var(--purple-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.breadcrumb {
    display: flex;
    justify-content: center;
    color: var(--text-gray);
}

/* Particles */
.particles {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    pointer-events: none;
}

.particle {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    pointer-events: none;
}

.particle:nth-child(1) {
    width: 60px;
    height: 60px;
    top: 20%;
    left: 10%;
    animation: float 15s infinite ease-in-out;
}

.particle:nth-child(2) {
    width: 30px;
    height: 30px;
    top: 60%;
    right: 15%;
    animation: float 8s infinite ease-in-out reverse;
}

.particle:nth-child(3) {
    width: 100px;
    height: 100px;
    bottom: 30%;
    left: 20%;
    animation: float 25s infinite linear;
    filter: blur(2px);
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    25% { transform: translate(20px, -30px) rotate(90deg); }
    50% { transform: translate(40px, 0) rotate(180deg); }
    75% { transform: translate(20px, 30px) rotate(270deg); }
}

/* Portfolio Single Container */
.portfolio-single {
    background: transparent;
}

/* Portfolio Header */
.portfolio-header {
    padding: 4rem 1.5rem 2rem;
    text-align: center;
}

.portfolio-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, var(--text-light), var(--purple-light));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: fadeInUp 0.6s ease;
}

.portfolio-excerpt {
    font-size: 1.2rem;
    line-height: 1.6;
    color: var(--text-gray);
    max-width: 700px;
    margin: 0 auto;
}

/* Hero Image */
.portfolio-hero {
    margin: 2rem auto;
}

.hero-container {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.hero-container:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.hero-image {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.7s ease;
}

.hero-container:hover .hero-image {
    transform: scale(1.05);
}

.hero-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 44px;
    height: 44px;
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--purple);
    transition: var(--transition);
}

.hero-container:hover .hero-badge {
    transform: scale(1.1) rotate(5deg);
    background: var(--purple);
    color: white;
}

/* Tech Stack Showcase */
.tech-stack-showcase {
    margin: 0rem auto;
    padding: 2rem;
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(108, 99, 255, 0.1);
    transition: var(--transition);
}

.tech-stack-showcase:hover {
    border-color: rgba(108, 99, 255, 0.3);
    box-shadow: var(--shadow-sm);
}

.tech-stack-header {
    text-align: center;
    margin-bottom: 2rem;
}

.tech-stack-header h3 {
    font-size: 1.8rem;
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

.tech-stack-header p {
    color: var(--text-gray);
}

.tech-stack-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
}

.tech-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    transition: var(--transition);
}

.tech-item:hover {
    transform: translateX(5px);
    background: rgba(108, 99, 255, 0.1);
}

.tech-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    border-radius: 12px;
    color: white;
    font-size: 1.5rem;
}

.tech-info h4 {
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.tech-count {
    font-size: 0.85rem;
    color: var(--text-gray);
}

/* Portfolio Body */
.portfolio-body {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 3rem;
    padding: 3rem 1.5rem;
    padding-bottom: 8rem !important;
}

.content-card {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid rgba(108, 99, 255, 0.1);
    transition: var(--transition);
}

.content-card:hover {
    border-color: rgba(108, 99, 255, 0.3);
    box-shadow: var(--shadow-sm);
}

.content-card h2,
.content-card h3,
.content-card h4 {
    color: var(--text-light);
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.content-card p {
    color: var(--text-gray);
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

.content-card ul,
.content-card ol {
    color: var(--text-gray);
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

.content-card li {
    margin-bottom: 0.5rem;
}

.content-card a {
    color: var(--purple);
    text-decoration: none;
    transition: var(--transition);
}

.content-card a:hover {
    color: var(--purple-light);
}

/* Modern Gallery */
.project-gallery {
    margin-top: 3rem;
}

.gallery-header {
    text-align: center;
    margin-bottom: 2rem;
}

.gallery-header h3 {
    font-size: 1.8rem;
    color: var(--text-light);
    margin-bottom: 0.5rem;
}

.gallery-header p {
    color: var(--text-gray);
}

.gallery-grid-modern {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.gallery-item-modern {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    cursor: pointer;
}

.gallery-item-modern:first-child {
    grid-column: span 2;
}

.gallery-link {
    position: relative;
    display: block;
    overflow: hidden;
}

.gallery-thumb {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.gallery-item-modern:hover .gallery-thumb {
    transform: scale(1.1);
}

.gallery-overlay-modern {
    position: absolute;
    inset: 0;
    background: rgba(108, 99, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: var(--transition);
    color: white;
}

.gallery-overlay-modern i {
    font-size: 2rem;
}

.gallery-item-modern:hover .gallery-overlay-modern {
    opacity: 1;
}

/* Sidebar */
.sidebar {
    position: sticky;
    top: 2rem;
    align-self: start;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

/* Info Card */
.info-card,
.author-box-modern,
.share-card {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.5rem;
    border: 1px solid rgba(108, 99, 255, 0.1);
    transition: var(--transition);
}

.info-card:hover,
.author-box-modern:hover,
.share-card:hover {
    border-color: rgba(108, 99, 255, 0.3);
    box-shadow: var(--shadow-sm);
    transform: translateY(-2px);
}

.info-card h3,
.author-box-modern h3,
.share-card h3 {
    color: var(--text-light);
    font-size: 1.3rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 12px;
    transition: var(--transition);
}

.info-item:hover {
    background: rgba(108, 99, 255, 0.1);
    transform: translateX(5px);
}

.info-item i {
    color: var(--purple);
    font-size: 1.2rem;
    margin-top: 0.2rem;
}

.info-item div {
    flex: 1;
}

.info-item strong {
    color: var(--text-light);
    display: block;
    margin-bottom: 0.25rem;
}

.info-item span {
    color: var(--text-gray);
    font-size: 0.9rem;
}

.project-link {
    color: var(--purple);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.project-link:hover {
    color: var(--purple-light);
    gap: 0.75rem;
}

/* Author Box Modern */
.author-header-modern {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.author-image-modern img {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--purple);
}

.author-info-modern h3 {
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.author-info-modern p {
    color: var(--text-gray);
    font-size: 0.85rem;
}

.author-bio-modern {
    color: var(--text-gray);
    line-height: 1.6;
    margin: 1rem 0;
    padding-top: 1rem;
    border-top: 1px solid rgba(108, 99, 255, 0.2);
}

.author-contact {
    margin-top: 1rem;
}

.contact-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: linear-gradient(135deg, var(--purple), var(--purple-dark));
    color: white;
    text-decoration: none;
    border-radius: 12px;
    font-size: 0.9rem;
    transition: var(--transition);
    width: 100%;
    justify-content: center;
        max-width: 200px;
}

.contact-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
}

/* Share Card */
.share-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.share-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
    color: var(--text-gray);
    text-decoration: none;
    transition: var(--transition);
}

.share-btn:hover {
    transform: translateY(-3px);
    color: white;
}

.share-btn.facebook:hover {
    background: #1877f2;
}

.share-btn.twitter:hover {
    background: #1da1f2;
}

.share-btn.linkedin:hover {
    background: #0077b5;
}

.share-btn.email:hover {
    background: #ea4335;
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

/* Responsive Design */
@media (max-width: 1024px) {
    .portfolio-body {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .sidebar {
        position: static;
    }
    
    .gallery-grid-modern {
        grid-template-columns: repeat(2, 1fr);
    }
}
@media (max-width:991px) {
.author-box-modern{
  text-align: center;  
}
.author-header-modern{
    justify-content: center;
}
}
@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
    }
    
    .blog-title {
        font-size: 2.5rem;
    }
    
    .portfolio-title {
        font-size: 2rem;
    }
    
    .portfolio-header {
        padding: 2rem 1rem;
    }
    
    .tech-stack-grid {
        grid-template-columns: 1fr;
    }
    
    .gallery-grid-modern {
        grid-template-columns: 1fr;
    }
    
    .gallery-item-modern:first-child {
        grid-column: span 1;
    }
    
    .gallery-thumb {
        height: 200px;
    }
    
    .content-card {
        padding: 1.5rem;
    }
    
    .info-card,
    .author-box-modern,
    .share-card {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .blog-title {
        font-size: 2rem;
    }
    
    .portfolio-title {
        font-size: 1.75rem;
    }
    
    .portfolio-excerpt {
        font-size: 1rem;
    }
    
    .tech-stack-header h3,
    .gallery-header h3 {
        font-size: 1.5rem;
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

/* Fancybox Integration */
.fancybox__container {
    --fancybox-bg: rgba(10, 10, 42, 0.95);
}

.fancybox__slide {
    padding: 2rem;
}
</style>

<!-- Add Font Awesome and Fancybox -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Fancybox
    Fancybox.bind('[data-fancybox]', {
        animated: true,
        showZoom: true,
        dragToClose: true,
    });
    
    // Copy email functionality
    const copyEmailBtn = document.querySelector('.copy-email-btn');
    if (copyEmailBtn) {
        copyEmailBtn.addEventListener('click', function() {
            const email = this.getAttribute('data-email');
            navigator.clipboard.writeText(email).then(() => {
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i> Copied!';
                this.classList.add('copy-success');
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('copy-success');
                }, 2000);
            });
        });
    }
});
</script>