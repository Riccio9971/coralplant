// Funzioni JavaScript comuni per il sito

// Gestione della navbar
document.addEventListener('DOMContentLoaded', function () {
    let prevScrollPos = window.pageYOffset;
    const navbar = document.querySelector('.nav-container');

    window.onscroll = function () {
        const currentScrollPos = window.pageYOffset;

        // Aggiungi classe per l'effetto di scroll
        if (currentScrollPos > 10) {
            navbar.classList.add('nav-scrolled');
        } else {
            navbar.classList.remove('nav-scrolled');
        }

        // Nascondi/mostra navbar durante lo scroll
        if (prevScrollPos > currentScrollPos) {
            navbar.classList.remove('nav-hidden');
        } else {
            navbar.classList.add('nav-hidden');
        }
        prevScrollPos = currentScrollPos;
    }

    // Gestione del menu hamburger
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger) {
        hamburger.addEventListener('click', function () {
            hamburger.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Chiudi il menu quando si clicca su un link
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });
    }
});

// Gestione carosello nella home page
function initializeCarousel() {
    const carouselTrack = document.querySelector('.carousel-track');
    if (!carouselTrack) return;

    const prevButton = document.querySelector('.carousel-button.prev');
    const nextButton = document.querySelector('.carousel-button.next');
    const carouselItems = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;
    let itemsPerView = 4; // Default per desktop

    // Funzione per aggiornare itemsPerView in base alla larghezza dello schermo
    function updateItemsPerView() {
        if (window.innerWidth < 768) {
            itemsPerView = 1;
        } else {
            itemsPerView = 4;
        }
    }

    function moveToSlide(index) {
        updateItemsPerView();

        const itemWidth = carouselItems[0].offsetWidth;
        const maxIndex = carouselItems.length - itemsPerView;

        // Assicura che l'indice non vada oltre i limiti
        currentIndex = Math.max(0, Math.min(index, maxIndex));

        // Calcola lo spostamento
        const offset = currentIndex * (itemWidth + 20); // 20px è il gap tra gli elementi

        carouselTrack.style.transform = `translateX(-${offset}px)`;
    }

    if (prevButton && nextButton) {
        prevButton.addEventListener('click', () => moveToSlide(currentIndex - 1));
        nextButton.addEventListener('click', () => moveToSlide(currentIndex + 1));
    }

    // Aggiorna il carosello su resize
    window.addEventListener('resize', () => {
        updateItemsPerView();
        moveToSlide(currentIndex);
    });

    // Inizializza la visualizzazione corretta al caricamento
    updateItemsPerView();
    moveToSlide(0);
}

// Animazione della timeline
function initializeTimeline() {
    const timelineItems = document.querySelectorAll('.timeline-item');
    if (timelineItems.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.5 });

    timelineItems.forEach(item => {
        observer.observe(item);
    });
}

// Inizializza tutte le funzionalità quando il DOM è pronto
document.addEventListener('DOMContentLoaded', function () {
    initializeCarousel();
    initializeTimeline();
});