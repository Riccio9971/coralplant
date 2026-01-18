/**
 * Script di miglioramento per l'interfaccia mobile di Coral Plant
 * Da includere dopo gli altri script
 */

document.addEventListener("DOMContentLoaded", function () {
    // 1. Migliora il comportamento della barra di ricerca
    enhanceSearchBar();

    // 2. Migliora l'interazione con i link e pulsanti
    enhanceMobileLinks();

    // 3. Gestisci meglio gli eventi di touch sui dispositivi mobili
    improveTouch();

    // 4. Migliora la navigazione tra categorie e varietà
    improveNavigation();

    // 5. Migliora il feedback visuale per l'utente
    addVisualFeedback();
});

/**
 * Migliora il comportamento della barra di ricerca
 */
function enhanceSearchBar() {
    const searchToggle = document.querySelector('.search-toggle');
    const header = document.querySelector('.mobile-header');
    const searchBar = document.querySelector('.search-bar');

    if (!searchToggle || !header || !searchBar) return;

    // Previeni la chiusura quando si clicca nella barra di ricerca
    searchBar.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    // Cambia l'effetto di apertura per evitare lo spazio bianco
    searchToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        if (header.classList.contains('expanded')) {
            header.classList.remove('expanded');

            // Aggiungi un piccolo ritardo prima di nascondere la barra di ricerca
            setTimeout(() => {
                searchBar.style.opacity = '0';
                searchBar.style.pointerEvents = 'none';
            }, 50);
        } else {
            header.classList.add('expanded');
            searchBar.style.opacity = '1';
            searchBar.style.pointerEvents = 'auto';

            // Focus sull'input di ricerca
            setTimeout(() => {
                const searchInput = document.getElementById('search-input');
                if (searchInput) searchInput.focus();
            }, 300);
        }
    });

    // Gestisci la chiusura della barra di ricerca quando si clicca altrove
    document.addEventListener('click', function (e) {
        if (header.classList.contains('expanded') && !header.contains(e.target)) {
            header.classList.remove('expanded');
        }
    });

    // Assicurati che il pulsante di cancellazione sia sempre visibile quando c'è del testo
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        // Aggiungi pulsante per cancellare la ricerca se non esiste già
        if (!document.querySelector('.search-clear')) {
            const clearButton = document.createElement('button');
            clearButton.type = 'button';
            clearButton.className = 'search-clear';
            clearButton.innerHTML = '<i class="fas fa-times-circle"></i>';
            clearButton.style.position = 'absolute';
            clearButton.style.right = '45px';
            clearButton.style.top = '50%';
            clearButton.style.transform = 'translateY(-50%)';
            clearButton.style.background = 'none';
            clearButton.style.border = 'none';
            clearButton.style.color = '#999';
            clearButton.style.fontSize = '1rem';
            clearButton.style.padding = '0';
            clearButton.style.display = 'none';
            clearButton.style.cursor = 'pointer';

            searchInput.parentNode.insertBefore(clearButton, searchInput.nextSibling);

            // Mostra/nascondi il pulsante in base al contenuto dell'input
            searchInput.addEventListener('input', function () {
                clearButton.style.display = this.value ? 'block' : 'none';
            });

            // Inizializza lo stato del pulsante
            clearButton.style.display = searchInput.value ? 'block' : 'none';

            // Aggiungi la funzionalità per cancellare la ricerca
            clearButton.addEventListener('click', function () {
                searchInput.value = '';
                searchInput.focus();
                this.style.display = 'none';
            });
        }
    }
}

/**
 * Migliora l'interazione con i link e pulsanti
 */
function enhanceMobileLinks() {
    // Risolve il problema del link "Torna alle varietà" che non funziona correttamente
    const backToVarietiesLinks = document.querySelectorAll('.reset-search, a[href*="torna alle varietà"]');

    backToVarietiesLinks.forEach(link => {
        // Estrai l'ID della specie dall'URL corrente
        const urlParams = new URLSearchParams(window.location.search);
        const specieId = urlParams.get('specie');

        if (specieId && link.textContent.includes('Torna alle varietà')) {
            // Correggi l'URL per tornare alla pagina delle varietà corretta
            link.href = `mobile-prodotti.php?specie=${specieId}`;

            // Aggiungi un'icona più appropriata
            if (link.querySelector('i.fas.fa-undo')) {
                link.querySelector('i.fas.fa-undo').className = 'fas fa-arrow-left';
            }
        }
    });

    // Migliora i link nelle breadcrumbs
    const breadcrumbLinks = document.querySelectorAll('.breadcrumb-link');
    breadcrumbLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Aggiungi effetto feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate(20);
            }
        });
    });
}

/**
 * Gestisci meglio gli eventi di touch sui dispositivi mobili
 */
function improveTouch() {
    // Aggiungi effetto di feedback tattile a tutti gli elementi cliccabili
    const touchElements = document.querySelectorAll('.product-card, .category-card, .reset-search, .view-all-categories, .breadcrumb-link, .footer-link');

    touchElements.forEach(element => {
        element.addEventListener('touchstart', function () {
            this.classList.add('touch-active');
        }, { passive: true });

        element.addEventListener('touchend', function () {
            setTimeout(() => {
                this.classList.remove('touch-active');
            }, 150);

            // Vibrazione sottile per feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate(20);
            }
        }, { passive: true });
    });

    // Gestisci swipe laterale per navigare tra le categorie
    const categoriesGrid = document.querySelector('.categories-grid');
    if (categoriesGrid) {
        let touchStartX = 0;
        let touchEndX = 0;

        categoriesGrid.addEventListener('touchstart', function (e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        categoriesGrid.addEventListener('touchend', function (e) {
            touchEndX = e.changedTouches[0].screenX;
            handleCategorySwipe();
        }, { passive: true });

        function handleCategorySwipe() {
            const threshold = 100; // Distanza minima per considerarla uno swipe

            if (touchEndX < touchStartX - threshold) {
                // Swipe a sinistra (avanti)
                goToNextCategory();
            } else if (touchEndX > touchStartX + threshold) {
                // Swipe a destra (indietro)
                goToPreviousCategory();
            }
        }

        function goToNextCategory() {
            // Troviamo la prossima categoria dopo quella attiva
            const activeCategory = document.querySelector('.category-card.active') ||
                document.querySelectorAll('.category-card')[0];
            if (activeCategory && activeCategory.nextElementSibling) {
                window.location.href = activeCategory.nextElementSibling.href;
            }
        }

        function goToPreviousCategory() {
            // Torniamo alla categoria precedente
            const activeCategory = document.querySelector('.category-card.active') ||
                document.querySelectorAll('.category-card')[0];
            if (activeCategory && activeCategory.previousElementSibling) {
                window.location.href = activeCategory.previousElementSibling.href;
            } else {
                // Se non c'è una categoria precedente, torniamo all'elenco principale
                window.location.href = 'mobile-prodotti.php';
            }
        }
    }
}

/**
 * Migliora la navigazione tra categorie e varietà
 */
function improveNavigation() {
    // Evidenzia la categoria o varietà corrente
    const urlParams = new URLSearchParams(window.location.search);
    const currentSpecieId = urlParams.get('specie');
    const currentVarietaId = urlParams.get('varieta');

    if (currentSpecieId) {
        const specieCards = document.querySelectorAll(`.category-card[href*="specie=${currentSpecieId}"]`);
        specieCards.forEach(card => card.classList.add('active'));
    }

    if (currentVarietaId) {
        const varietaCards = document.querySelectorAll(`.category-card[href*="varieta=${currentVarietaId}"]`);
        varietaCards.forEach(card => card.classList.add('active'));
    }

    // Gestione migliorata della navigazione tra categorie e varietà
    const categoryCards = document.querySelectorAll('.category-card');

    categoryCards.forEach(card => {
        card.addEventListener('click', function (e) {
            // Aggiungi un effetto di caricamento
            this.style.transform = 'scale(0.98)';
            this.style.opacity = '0.8';

            // Feedback tattile
            if ('vibrate' in navigator) {
                navigator.vibrate(30);
            }
        });
    });
}

/**
 * Aggiunge un feedback visuale per migliorare l'esperienza utente
 */
function addVisualFeedback() {
    // Aggiungi classe per styling migliorato ai messaggi di assenza prodotti
    const emptyProductsMessages = document.querySelectorAll('.empty-products-message');

    emptyProductsMessages.forEach(message => {
        // Assicurati che l'icona sia visivamente più evidenziata
        const icon = message.querySelector('i');
        if (icon) {
            icon.style.fontSize = '3.5rem';
            icon.style.opacity = '0.7';

            // Se l'icona è fa-search, cambiala in qualcosa di più adatto
            if (icon.classList.contains('fa-search')) {
                icon.className = 'fas fa-seedling';
            }
        }

        // Assicurati che i pulsanti abbiano feedback touch
        const buttons = message.querySelectorAll('a, button');
        buttons.forEach(button => {
            button.addEventListener('touchstart', function () {
                this.style.transform = 'scale(0.97)';
            }, { passive: true });

            button.addEventListener('touchend', function () {
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            }, { passive: true });
        });
    });

    // Migliora il feedback visuale dei pulsanti di filtro
    const filterToggles = document.querySelectorAll('.mobile-filter-toggle, .filter-category, .filter-link');

    filterToggles.forEach(toggle => {
        toggle.addEventListener('touchstart', function () {
            this.style.transform = 'scale(0.98)';
        }, { passive: true });

        toggle.addEventListener('touchend', function () {
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        }, { passive: true });
    });
}