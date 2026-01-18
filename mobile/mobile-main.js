/**
 * Script principale per le pagine mobile di Coral Plant
 * Unifica e migliora la funzionalità JavaScript per tutte le pagine mobile
 */

// Namespace per l'applicazione
const CoralPlantMobile = {
    // Inizializzazione principale
    init: function () {
        // Rileva le funzionalità da attivare in base agli elementi presenti nella pagina
        this.detectAndInitialize();

        // Inizializza funzionalità comuni a tutte le pagine
        this.initializeCommon();
    },

    // Rileva gli elementi nella pagina e inizializza le funzionalità appropriate
    detectAndInitialize: function () {
        // Menu Mobile
        if (document.querySelector('.menu-toggle')) {
            this.initializeMobileMenu();
        }

        // Search Toggle
        if (document.querySelector('.search-toggle')) {
            this.initializeSearchToggle();
        }

        // Carosello Prodotti
        if (document.getElementById('products-carousel')) {
            this.initializeProductCarousel();
        }

        // Timeline
        if (document.querySelectorAll('.timeline-item').length > 0) {
            this.initializeTimeline();
        }

        // Pulsante Torna Su
        if (document.getElementById('back-to-top')) {
            this.initializeBackToTop();
        }

        // Filtri Prodotti
        if (document.querySelector('.filter-chips')) {
            this.initializeProductFilters();
        }

        // Modale Prodotto
        if (document.getElementById('product-modal')) {
            this.initializeProductModal();
        }

        // Pagina Prodotti con Grid
        if (document.querySelector('.products-grid')) {
            this.initializeProductsGrid();
        }

        // Form di contatto
        if (document.querySelector('.contact-form')) {
            this.initializeContactForm();
        }

        // Form newsletter
        if (document.querySelector('.newsletter-form')) {
            this.initializeNewsletterForm();
        }
    },

    // Inizializza funzionalità comuni a tutte le pagine
    initializeCommon: function () {
        // Gestione link di navigazione interni
        this.initializeNavLinks();

        // Effetto hover per touch devices
        this.initializeTouchEffects();
    },

    // Menu Mobile
    initializeMobileMenu: function () {
        const menuToggle = document.querySelector('.menu-toggle');
        const header = document.querySelector('.mobile-header');

        if (!menuToggle || !header) return;

        menuToggle.addEventListener('click', function () {
            header.classList.toggle('expanded');

            // Cambia l'icona del menu
            const icon = this.querySelector('i');
            if (icon) {
                if (header.classList.contains('expanded')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            }

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate(30);
            }
        });

        // Chiudi menu al click su link interni
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function () {
                if (header.classList.contains('expanded')) {
                    setTimeout(() => {
                        header.classList.remove('expanded');
                        const icon = menuToggle.querySelector('i');
                        if (icon) {
                            icon.classList.remove('fa-times');
                            icon.classList.add('fa-bars');
                        }
                    }, 300);
                }
            });
        });
    },

    // Toggle barra di ricerca
    initializeSearchToggle: function () {
        const searchToggle = document.querySelector('.search-toggle');
        const header = document.querySelector('.mobile-header');

        if (!searchToggle || !header) return;

        searchToggle.addEventListener('click', function () {
            header.classList.toggle('expanded');

            // Focus sull'input di ricerca se il menu è espanso
            if (header.classList.contains('expanded')) {
                const searchInput = document.getElementById('search-input');
                if (searchInput) {
                    searchInput.focus();
                }
            }
        });
    },

    // Carosello Prodotti
    initializeProductCarousel: function () {
        const carousel = document.getElementById('products-carousel');
        const paginationContainer = document.getElementById('carousel-pagination');

        if (!carousel || !paginationContainer) return;

        // Variabili per gestire lo scorrimento touch
        let touchStartX = 0;
        let touchEndX = 0;
        let currentIndex = 0;
        const slides = carousel.querySelectorAll('.carousel-slide');
        const totalSlides = slides.length;

        if (totalSlides === 0) return;

        carousel.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        carousel.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const threshold = 50; // Minima distanza per considerarlo uno swipe

            if (touchStartX - touchEndX > threshold) {
                // Swipe a sinistra (avanti)
                if (currentIndex < totalSlides - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            } else if (touchEndX - touchStartX > threshold) {
                // Swipe a destra (indietro)
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }
        }

        function updateCarousel() {
            // Calcola lo spostamento
            const slideWidth = slides[0].offsetWidth;
            carousel.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

            // Aggiorna i puntini di paginazione
            const dots = paginationContainer.querySelectorAll('.pagination-dot');
            dots.forEach((dot, index) => {
                if (index === currentIndex) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate(20);
            }
        }

        // Gestisci il click sui puntini di paginazione
        const dots = paginationContainer.querySelectorAll('.pagination-dot');
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function () {
                currentIndex = index;
                updateCarousel();
            });
        });

        // Rotazione automatica del carosello (solo se la pagina è visibile)
        let autoRotateInterval = setInterval(() => {
            if (document.visibilityState === 'visible') {
                currentIndex = (currentIndex + 1) % totalSlides;
                updateCarousel();
            }
        }, 5000);

        // Pulisci l'intervallo quando l'utente naviga via dalla pagina
        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'hidden') {
                clearInterval(autoRotateInterval);
            } else {
                // Riavvia l'intervallo
                clearInterval(autoRotateInterval);
                autoRotateInterval = setInterval(() => {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    updateCarousel();
                }, 5000);
            }
        });
    },

    // Timeline con animazione
    initializeTimeline: function () {
        const timelineItems = document.querySelectorAll('.timeline-item');

        if (timelineItems.length === 0) return;

        // Utilizziamo Intersection Observer per animare gli elementi quando diventano visibili
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in-up');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        timelineItems.forEach(item => {
            observer.observe(item);
        });
    },

    // Pulsante Torna Su
    initializeBackToTop: function () {
        const backToTopBtn = document.getElementById('back-to-top');

        if (!backToTopBtn) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTopBtn.classList.add('visible');
            } else {
                backToTopBtn.classList.remove('visible');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate([15, 10, 15]);
            }
        });
    },

    // Link di navigazione interni
    initializeNavLinks: function () {
        const navLinks = document.querySelectorAll('.nav-link, .footer-link');

        navLinks.forEach(link => {
            // Gestisci solo i link interni con hash
            if (link.getAttribute('href') && link.getAttribute('href').startsWith('#')) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    const targetId = this.getAttribute('href');
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        // Scorrimento fluido all'elemento target con offset per l'header fisso
                        window.scrollTo({
                            top: targetElement.offsetTop - 70,
                            behavior: 'smooth'
                        });

                        // Aggiorna classe active nel menu
                        document.querySelectorAll('.nav-item').forEach(item => {
                            item.classList.remove('active');
                        });

                        const parentItem = this.closest('.nav-item');
                        if (parentItem) {
                            parentItem.classList.add('active');
                        }

                        // Aggiorna classe active nel footer
                        document.querySelectorAll('.footer-link').forEach(fLink => {
                            fLink.classList.remove('active');
                        });

                        const footerLink = document.querySelector(`.footer-link[href="${targetId}"]`);
                        if (footerLink) {
                            footerLink.classList.add('active');
                        }
                    }
                });
            }
        });
    },

    // Filtri Prodotti (chip)
    initializeProductFilters: function () {
        const chips = document.querySelectorAll('.chip');

        chips.forEach(chip => {
            chip.addEventListener('click', function () {
                // Aggiungi classe active e rimuovila dagli altri
                chips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;

                // Qui dovresti implementare la funzione di filtro specifica per la tua applicazione
                // Ad esempio, potresti chiamare una funzione che filtra i prodotti in base al valore di 'filter'

                // Effetto di feedback tattile
                if ('vibrate' in navigator) {
                    navigator.vibrate(50);
                }
            });
        });
    },

    // Modale Prodotto
    initializeProductModal: function () {
        const modal = document.getElementById('product-modal');
        const closeButtons = document.querySelectorAll('.close-modal, .modal-close-btn');

        if (!modal) return;

        // Funzione per aprire la modale (da chiamare con i dati del prodotto)
        window.openProductModal = function (productData) {
            // Popola la modale con i dati del prodotto
            document.getElementById('modal-title').textContent = productData.titolo || productData.title;

            const modalImage = document.getElementById('modal-image');
            if (modalImage) {
                if (productData.nome_file) {
                    // Formato del database
                    modalImage.src = 'uploads/piante/' + productData.nome_file;
                } else {
                    // Formato diretto
                    modalImage.src = productData.image;
                }
                modalImage.alt = productData.titolo || productData.title;
            }

            const specieValue = document.getElementById('modal-specie-value');
            if (specieValue) {
                specieValue.textContent = productData.specie_nome || productData.category;
            }

            const varietaValue = document.getElementById('modal-varieta-value');
            if (varietaValue) {
                varietaValue.textContent = productData.varieta_nome || productData.variety;
            }

            const description = document.getElementById('modal-description');
            if (description) {
                description.textContent = productData.descrizione || productData.description;
            }

            // Gestione badge principale
            const badgePrincipale = document.getElementById('modal-badge-principale');
            if (badgePrincipale) {
                if (productData.is_principale || productData.isPopular) {
                    badgePrincipale.style.display = 'flex';
                } else {
                    badgePrincipale.style.display = 'none';
                }
            }

            // Mostra la modale con animazione
            modal.classList.add('active');

            // Previeni lo scorrimento della pagina
            document.body.style.overflow = 'hidden';
        };

        // Funzione per chiudere la modale
        window.closeProductModal = function () {
            modal.classList.remove('active');

            // Ripristina lo scorrimento
            document.body.style.overflow = '';
        };

        // Gestione chiusura
        closeButtons.forEach(button => {
            button.addEventListener('click', window.closeProductModal);
        });

        // Chiusura con tap fuori dal contenuto
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                window.closeProductModal();
            }
        });
    },

    // Griglia Prodotti
    initializeProductsGrid: function () {
        const productCards = document.querySelectorAll('.product-card');

        productCards.forEach(card => {
            card.addEventListener('click', function () {
                const productId = this.dataset.id;

                // Se è stata definita una variabile globale prodottiData, cerca il prodotto
                if (window.prodottiData && productId) {
                    const product = window.prodottiData.find(p => p.id == productId);

                    if (product && window.openProductModal) {
                        window.openProductModal(product);
                    }
                }
            });
        });
    },

    // Form di contatto
    initializeContactForm: function () {
        const contactForm = document.querySelector('.contact-form');

        if (!contactForm) return;

        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Simulazione invio form
            const submitBtn = this.querySelector('.submit-button');
            const originalText = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Invio in corso...';
            submitBtn.disabled = true;

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate([30, 50, 30]);
            }

            // Simula richiesta di invio
            setTimeout(() => {
                // Mostra messaggio di successo
                const formGroups = contactForm.querySelectorAll('.form-group');
                formGroups.forEach(group => {
                    group.style.display = 'none';
                });

                submitBtn.style.display = 'none';

                const successMessage = document.createElement('div');
                successMessage.className = 'success-message';
                successMessage.innerHTML = `
                    <div style="text-align: center; padding: 20px;">
                        <i class="fas fa-check-circle" style="color: var(--primary-color); font-size: 3rem; margin-bottom: 15px;"></i>
                        <h3 style="color: var(--accent-color); margin-bottom: 10px;">Messaggio inviato!</h3>
                        <p>Grazie per averci contattato. Ti risponderemo al più presto.</p>
                    </div>
                `;

                contactForm.appendChild(successMessage);
            }, 1500);
        });
    },

    // Form newsletter
    initializeNewsletterForm: function () {
        const newsletterForm = document.querySelector('.newsletter-form');

        if (!newsletterForm) return;

        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button');

            // Verifica semplice dell'email
            const email = emailInput.value.trim();
            if (!email || !email.includes('@')) {
                emailInput.style.boxShadow = '0 0 0 3px rgba(255, 0, 0, 0.3)';
                return;
            }

            // Simulazione iscrizione newsletter
            const originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitBtn.disabled = true;
            emailInput.disabled = true;

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate([20, 30, 20]);
            }

            // Simula richiesta di iscrizione
            setTimeout(() => {
                // Mostra messaggio di successo
                newsletterForm.innerHTML = `
                    <div style="text-align: center; background: rgba(255, 255, 255, 0.2); padding: 15px; border-radius: 8px;">
                        <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <p>Grazie per l'iscrizione alla nostra newsletter!</p>
                    </div>
                `;
            }, 1500);
        });
    },

    // Effetti touch per i dispositivi mobili
    initializeTouchEffects: function () {
        // Aggiungi effetto hover temporaneo ai pulsanti e card quando toccati
        const touchElements = document.querySelectorAll('.product-card, .category-card, .btn, .feature-card');

        touchElements.forEach(element => {
            element.addEventListener('touchstart', function () {
                this.classList.add('hover-effect');
            }, { passive: true });

            element.addEventListener('touchend', function () {
                setTimeout(() => {
                    this.classList.remove('hover-effect');
                }, 150);
            }, { passive: true });
        });
    }
};

// Inizializza l'applicazione quando il DOM è pronto
document.addEventListener('DOMContentLoaded', function () {
    CoralPlantMobile.init();
});