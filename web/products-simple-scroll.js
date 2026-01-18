/**
 * Soluzione semplice per mantenere la sidebar scrollata all'elemento selezionato
 * Questo script si occupa di scrollare automaticamente la sidebar all'elemento
 * attivo dopo il caricamento della pagina.
 */

(function() {
    'use strict';

    console.log('[Sidebar Scroll] Inizializzazione...');

    // Attendi che il DOM sia completamente caricato
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        const sidebar = document.querySelector('.sidebar');
        if (!sidebar) {
            console.warn('[Sidebar Scroll] Sidebar non trovata');
            return;
        }

        // Trova l'elemento attivo nella sidebar
        const activeElement = findActiveElement();

        if (activeElement) {
            console.log('[Sidebar Scroll] Elemento attivo trovato:', activeElement);

            // Scroll l'elemento al centro della sidebar con un piccolo delay
            // per assicurarsi che la pagina sia completamente renderizzata
            setTimeout(() => {
                scrollElementIntoView(sidebar, activeElement);
            }, 100);
        } else {
            console.log('[Sidebar Scroll] Nessun elemento attivo trovato');
        }
    }

    /**
     * Trova l'elemento attivo nella sidebar in base ai parametri URL
     */
    function findActiveElement() {
        // Cerca l'elemento con classe 'active'
        let activeElement = document.querySelector('.filter-item.active');

        if (activeElement) {
            console.log('[Sidebar Scroll] Trovato filter-item active');
            return activeElement;
        }

        // Se non trovato, cerca una categoria attiva
        activeElement = document.querySelector('.filter-category.active');

        if (activeElement) {
            console.log('[Sidebar Scroll] Trovato filter-category active');
            return activeElement;
        }

        // Come fallback, cerca in base ai parametri URL
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('varieta')) {
            const varietaId = urlParams.get('varieta');
            activeElement = document.querySelector(`.filter-item[data-variety-id="${varietaId}"]`);
            if (activeElement) {
                console.log('[Sidebar Scroll] Trovato elemento varietà da URL:', varietaId);
                return activeElement;
            }
        }

        if (urlParams.has('specie')) {
            const specieId = urlParams.get('specie');
            activeElement = document.querySelector(`.filter-category[data-url*="specie=${specieId}"]`);
            if (activeElement) {
                console.log('[Sidebar Scroll] Trovato elemento specie da URL:', specieId);
                return activeElement;
            }
        }

        return null;
    }

    /**
     * Scrolla l'elemento al centro della sidebar
     */
    function scrollElementIntoView(sidebar, element) {
        // Ottieni le dimensioni
        const sidebarRect = sidebar.getBoundingClientRect();
        const elementRect = element.getBoundingClientRect();

        // Calcola la posizione per centrare l'elemento
        const sidebarScrollTop = sidebar.scrollTop;
        const elementOffsetTop = element.offsetTop;
        const sidebarHeight = sidebarRect.height;
        const elementHeight = elementRect.height;

        // Calcola lo scroll target (elemento al centro della sidebar)
        const scrollTarget = elementOffsetTop - (sidebarHeight / 2) + (elementHeight / 2);

        console.log('[Sidebar Scroll] Scrolling to:', scrollTarget);

        // Scrolla con animazione fluida
        sidebar.scrollTo({
            top: Math.max(0, scrollTarget), // Non andare sotto 0
            behavior: 'smooth'
        });

        // Aggiungi un highlight temporaneo per feedback visivo
        element.style.transition = 'background-color 0.3s';
        const originalBg = window.getComputedStyle(element).backgroundColor;

        // Flash effetto
        element.style.backgroundColor = 'rgba(126, 162, 56, 0.2)'; // primary-color con opacity
        setTimeout(() => {
            element.style.backgroundColor = originalBg;
            setTimeout(() => {
                element.style.transition = '';
            }, 300);
        }, 600);
    }
})();
