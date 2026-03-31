jQuery(document).ready(function ($) {
    $('.featured-projects').owlCarousel({
        items: 3,
        loop: true,
        margin: 20,
        autoplay: true,
        autoplayTimeout: 4000,
        nav: true,
        dots: false,
        autoplayHoverPause:true,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1024: { items: 3 }
        }
    });
});



document.addEventListener("DOMContentLoaded", function() {
    const loadMoreBtn = document.getElementById('fp-loadmore');
    
    if (!loadMoreBtn) return;

    // Get all hidden cards
    const getHiddenCards = () => document.querySelectorAll('.fp-card.fp-hidden');
    
    // Update counter
    const updateCounter = () => {
        const shownCards = document.querySelectorAll('.fp-card:not(.fp-hidden)').length;
        const totalCards = document.querySelectorAll('.fp-card').length;
        const counterElement = document.querySelector('.fp-counter .fp-shown');
        if (counterElement) {
            counterElement.textContent = shownCards;
        }
        
        // Hide load more button if no more hidden cards
        if (shownCards >= totalCards) {
            loadMoreBtn.style.opacity = '0';
            loadMoreBtn.style.pointerEvents = 'none';
            setTimeout(() => {
                loadMoreBtn.style.display = 'none';
            }, 300);
        }
    };

    // Load more functionality
    loadMoreBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        const hiddenCards = getHiddenCards();
        if (hiddenCards.length === 0) return;
        
        // Add loading state
        this.classList.add('loading');
        this.disabled = true;
        
        // Simulate loading (remove this in production)
        setTimeout(() => {
            // Show next 2 hidden cards
            let shownCount = 0;
            
            for (let i = 0; i < 2; i++) {
                if (hiddenCards[i]) {
                    hiddenCards[i].classList.remove('fp-hidden');
                    shownCount++;
                }
            }
            
            // Remove loading state
            this.classList.remove('loading');
            this.disabled = false;
            
            // Update counter
            updateCounter();
            
            // Smooth scroll to first new card if needed
            if (shownCount > 0) {
                const lastVisibleCard = document.querySelectorAll('.fp-card:not(.fp-hidden)')[document.querySelectorAll('.fp-card:not(.fp-hidden)').length - 2];
                if (lastVisibleCard) {
                    lastVisibleCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }
            
        }, 500); // Simulated delay - remove in production
    });

    // Initial counter setup
    updateCounter();
});