// JavaScript avanzato per la pagina dei prodotti di Coral Plant
document.addEventListener('DOMContentLoaded', function () {
    console.log("Inizializzazione script prodotti migliorato");

    // 1. Miglioramento dell'interattività della sidebar
    initSidebarEnhancements();

    // 2. Attivazione animazioni card innovative
    initCardAnimations();

    // 3. Gestione della modale dei prodotti
    initProductModal();

    // 4. Gestione filtri mobili
    initMobileFilters();

    // 5. Altre funzionalità 
    initMiscFeatures();

    // Gestione filtri mobili
    function initMobileFilters() {
        const mobileFilterBtn = document.querySelector('.mobile-filter-toggle');
        const sidebar = document.querySelector('.sidebar');

        if (mobileFilterBtn && sidebar) {
            mobileFilterBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                mobileFilterBtn.classList.toggle('active');

                if (sidebar.classList.contains('active')) {
                    mobileFilterBtn.innerHTML = '<i class="fas fa-times"></i> Nascondi Filtri';
                } else {
                    mobileFilterBtn.innerHTML = '<i class="fas fa-filter"></i> Mostra Filtri';
                }

                // Feedback tattile su mobile
                if ('vibrate' in navigator) {
                    navigator.vibrate(30);
                }
            });
        }
    }

    // Funzione per gestire le animazioni delle card quando si cambia filtro
    function initCardAnimations() {
        const urlParams = new URLSearchParams(window.location.search);
        const varietaId = urlParams.get('varieta');

        // Applica l'animazione se si è filtrato per varietà
        if (varietaId) {
            const productsGrid = document.querySelector('.products-grid');
            const cards = document.querySelectorAll('.product-card');

            if (productsGrid && cards.length > 0) {
                // Aggiungi classe per attivare effetti a cascata
                productsGrid.classList.add('filtering');

                // Applica l'animazione a ogni card
                cards.forEach(card => {
                    // Rimuovi eventuali classi di animazione precedenti
                    card.classList.remove('filter-animation');

                    // Forza un reflow per far ripartire l'animazione
                    void card.offsetWidth;

                    // Aggiungi la classe per l'animazione
                    card.classList.add('filter-animation');
                });

                // Dopo che tutte le animazioni sono complete, rimuovi la classe filtering
                setTimeout(() => {
                    productsGrid.classList.remove('filtering');
                }, 1500); // Durata leggermente superiore alla somma dell'animazione più lunga
            }
        }
    }

    // Funzione per migliorare l'interattività della sidebar
    function initSidebarEnhancements() {
        // Gestione click su categoria (intera area)
        document.querySelectorAll('.filter-category').forEach(category => {
            category.addEventListener('click', function (e) {
                // Se il click è sull'icona, espandi/collassa senza navigare
                if (e.target.closest('.filter-icon')) {
                    e.preventDefault();
                    const items = this.nextElementSibling;
                    const icon = this.querySelector('.filter-icon');

                    items.classList.toggle('expanded');
                    icon.classList.toggle('expanded');

                    // Feedback tattile su mobile
                    if ('vibrate' in navigator) {
                        navigator.vibrate(20);
                    }
                    return;
                }

                // Altrimenti naviga all'URL associato
                const url = this.getAttribute('data-url');
                if (url) {
                    // Aggiungi un effetto di caricamento 
                    document.body.classList.add('loading-transition');

                    // Naviga all'URL
                    window.location.href = url;
                }
            });
        });

        // Gestione click su elemento varietà (intera area)
        document.querySelectorAll('.filter-item').forEach(item => {
            item.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                if (url) {
                    // Aggiungi un effetto di caricamento 
                    document.body.classList.add('loading-transition');

                    // Feedback tattile su mobile
                    if ('vibrate' in navigator) {
                        navigator.vibrate(25);
                    }

                    // Naviga all'URL con un leggero ritardo per permettere l'effetto visivo
                    setTimeout(() => {
                        window.location.href = url;
                    }, 100);
                }
            });
        });

        // Aggiungi una classe al body quando viene selezionata una varietà
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('varieta')) {
            document.body.classList.add('variety-selected');

            // Evidenzia visivamente l'elemento della varietà selezionata
            const varietyId = urlParams.get('varieta');
            const selectedItem = document.querySelector(`.filter-item[data-variety-id="${varietyId}"]`);
            if (selectedItem) {
                selectedItem.classList.add('active');

                // Assicurati che il genitore (lista delle varietà) sia espanso
                const parentList = selectedItem.closest('.filter-items');
                if (parentList) {
                    parentList.classList.add('expanded');

                    // Assicurati che l'icona della categoria sia espansa
                    const categoryIcon = parentList.previousElementSibling.querySelector('.filter-icon');
                    if (categoryIcon) {
                        categoryIcon.classList.add('expanded');
                    }

                    // Assicurati che la categoria sia marcata come attiva
                    const category = parentList.previousElementSibling;
                    if (category) {
                        category.classList.add('active');
                    }
                }

                // NON fare scroll automatico - mantieni la posizione della sidebar
            }
        }

        // Gestisci anche il caso in cui sia selezionata solo la specie
        if (urlParams.has('specie') && !urlParams.has('varieta')) {
            const specieId = urlParams.get('specie');
            const specieCategory = document.querySelector(`.filter-category[data-url*="specie=${specieId}"]`);

            if (specieCategory) {
                // NON fare scroll automatico - mantieni la posizione della sidebar
            }
        }
    }

    function initProductModal() {
        const productModal = document.getElementById('product-modal');
        const modalClose = document.querySelector('.modal-close');
        const modalCloseBtn = document.querySelector('.modal-close-btn');
        const productCards = document.querySelectorAll('.product-card');
        const detailBtns = document.querySelectorAll('.view-details-btn');

        // Array per tenere traccia delle foto del prodotto corrente
        let currentProductImages = [];
        let currentImageIndex = 0;

        // Funzione per caricare le foto aggiuntive di un prodotto
        async function loadAdditionalPhotos(productId) {
            try {
                const response = await fetch(`/web/get-additional-photos.php?product_id=${productId}`);
                const data = await response.json();

                if (data.error) {
                    console.error("Errore nel caricamento delle foto aggiuntive:", data.error);
                    return [];
                }

                return data.photos || [];
            } catch (error) {
                console.error("Errore nella richiesta delle foto aggiuntive:", error);
                return [];
            }
        }

        // Funzione per aggiornare lo slider della modale
        function updateModalSlider(index) {
            const modalImage = document.getElementById('modal-image');
            const sliderControls = document.querySelector('.slider-controls');

            if (!modalImage || currentProductImages.length === 0) return;

            // Assicurati che l'indice sia valido (ciclo circolare)
            if (index < 0) index = currentProductImages.length - 1;
            if (index >= currentProductImages.length) index = 0;

            currentImageIndex = index;

            // Aggiorna l'immagine
            modalImage.src = currentProductImages[index];

            // Aggiorna i puntini di navigazione
            if (sliderControls) {
                const dots = sliderControls.querySelectorAll('.slider-dot');
                dots.forEach((dot, i) => {
                    if (i === index) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Feedback tattile su mobile
            if ('vibrate' in navigator) {
                navigator.vibrate(15);
            }
        }

        // Aggiungi eventi per le frecce di navigazione
        const prevButton = document.querySelector('.prev-image');
        const nextButton = document.querySelector('.next-image');

        if (prevButton) {
            prevButton.addEventListener('click', (e) => {
                e.stopPropagation();
                updateModalSlider(currentImageIndex - 1);
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', (e) => {
                e.stopPropagation();
                updateModalSlider(currentImageIndex + 1);
            });
        }

        // Funzione per aprire la modale con i dettagli del prodotto e caricare le foto aggiuntive
        window.openProductModal = async function(productData) {
            if (!productData || !productModal) return;

            console.log("Apertura modale per prodotto:", productData.titolo || "unknown");

            // Elementi della modale
            const modalTitle = document.getElementById('modal-title');
            const modalImage = document.getElementById('modal-image');
            const modalDescription = document.getElementById('modal-description');
            const modalSpecieValue = document.getElementById('modal-specie-value');
            const modalVarietaValue = document.getElementById('modal-varieta-value');
            const sliderControls = document.querySelector('.slider-controls');
            const imageSlider = document.querySelector('.image-slider');

            // Gestione visibilità delle frecce di navigazione
            if (prevButton) prevButton.style.display = 'none';
            if (nextButton) nextButton.style.display = 'none';

            // Reset delle variabili delle immagini
            currentProductImages = [];
            currentImageIndex = 0;

            // Popolamento dati nella modale
            if (modalTitle) modalTitle.textContent = productData.titolo || '';

            // Preparazione del percorso dell'immagine principale
            let mainImagePath;
            if (productData.nome_file) {
                // Formato del database
                mainImagePath = '../admin/uploads/piante/' + productData.nome_file;
            } else {
                // Formato diretto (fallback)
                mainImagePath = productData.image || '../images/placeholder-product.jpg';
            }

            // Aggiungi l'immagine principale all'array
            currentProductImages.push(mainImagePath);

            // Carica le foto aggiuntive se esiste l'ID del prodotto
            if (productData.id) {
                try {
                    const additionalPhotos = await loadAdditionalPhotos(productData.id);

                    // Aggiungi i percorsi delle foto aggiuntive all'array
                    additionalPhotos.forEach(photo => {
                        const photoPath = '../admin/uploads/piante/' + photo.nome_file;
                        currentProductImages.push(photoPath);
                    });

                    // Aggiorna i controlli dello slider se ci sono più foto
                    if (sliderControls && currentProductImages.length > 1) {
                        // Svuota i controlli esistenti
                        sliderControls.innerHTML = '';

                        // Crea un puntino per ogni foto
                        currentProductImages.forEach((_, i) => {
                            const dot = document.createElement('button');
                            dot.className = 'slider-dot' + (i === 0 ? ' active' : '');
                            dot.setAttribute('data-index', i);
                            dot.addEventListener('click', () => updateModalSlider(i));
                            sliderControls.appendChild(dot);
                        });

                        // Mostra i controlli e le frecce solo se ci sono più foto
                        sliderControls.style.display = 'flex';
                        if (prevButton) prevButton.style.display = 'flex';
                        if (nextButton) nextButton.style.display = 'flex';
                    } else if (sliderControls) {
                        // Nascondi i controlli se c'è solo un'immagine
                        sliderControls.style.display = 'none';
                    }
                } catch (error) {
                    console.error("Errore nel caricamento delle foto aggiuntive:", error);
                }
            }

            // Gestione sicura dell'immagine
            if (modalImage) {
                modalImage.src = mainImagePath;
                modalImage.alt = productData.titolo || '';

                // In caso di errore dell'immagine, usa un'immagine di fallback
                modalImage.onerror = function() {
                    console.warn("Errore caricamento immagine:", mainImagePath);
                    this.src = '../images/placeholder-product.jpg';
                    this.onerror = null; // Previene loop infiniti
                };
            }

            // Popola i dati di specie e varietà
            if (modalSpecieValue) modalSpecieValue.textContent = productData.specie_nome || '';
            if (modalVarietaValue) modalVarietaValue.textContent = productData.varieta_nome || '';

            // Popola la descrizione
            if (modalDescription) modalDescription.textContent = productData.descrizione || '';

            // Aggiungi gestori degli eventi per lo swipe sullo slider
            const sliderContainer = document.querySelector('.slider-container');
            if (sliderContainer && currentProductImages.length > 1) {
                let touchStartX = 0;
                let touchEndX = 0;

                // Rimuovi handler precedenti (per sicurezza)
                sliderContainer.removeEventListener('touchstart', handleTouchStart);
                sliderContainer.removeEventListener('touchend', handleTouchEnd);

                // Aggiungi handler per il touchstart
                function handleTouchStart(e) {
                    touchStartX = e.changedTouches[0].screenX;
                }
                sliderContainer.addEventListener('touchstart', handleTouchStart, { passive: true });

                // Aggiungi handler per il touchend
                function handleTouchEnd(e) {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                }
                sliderContainer.addEventListener('touchend', handleTouchEnd, { passive: true });

                // Funzione per gestire lo swipe
                function handleSwipe() {
                    const threshold = 50; // Distanza minima per considerarlo uno swipe

                    if (touchStartX - touchEndX > threshold) {
                        // Swipe a sinistra (immagine successiva)
                        updateModalSlider(currentImageIndex + 1);
                    } else if (touchEndX - touchStartX > threshold) {
                        // Swipe a destra (immagine precedente)
                        updateModalSlider(currentImageIndex - 1);
                    }
                }
            }

            // Mostra la modale con animazione
            productModal.classList.add('active');
            document.body.classList.add('modal-open');

            // Feedback tattile su mobile
            if ('vibrate' in navigator) {
                navigator.vibrate([15, 30, 15]);
            }
        };

        // Funzione per chiudere la modale
        function closeProductModal() {
            if (!productModal) return;

            productModal.classList.remove('active');
            document.body.classList.remove('modal-open');

            // Feedback tattile su mobile
            if ('vibrate' in navigator) {
                navigator.vibrate(20);
            }

            // Reset delle variabili delle immagini
            currentProductImages = [];
            currentImageIndex = 0;
        }

        // Eventi per chiudere la modale
        if (modalClose) {
            modalClose.addEventListener('click', closeProductModal);
        }

        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', closeProductModal);
        }

        // Chiudi la modale con il tasto ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && productModal && productModal.classList.contains('active')) {
                closeProductModal();
            }
        });

        // Chiudi la modale se si clicca fuori dal contenuto
        if (productModal) {
            productModal.addEventListener('click', function(e) {
                if (e.target === productModal) {
                    closeProductModal();
                }
            });
        }

        // Gestione navigazione con tastiera nelle foto (frecce sinistra/destra)
        document.addEventListener('keydown', function(e) {
            if (productModal && productModal.classList.contains('active') && currentProductImages.length > 1) {
                if (e.key === 'ArrowLeft') {
                    updateModalSlider(currentImageIndex - 1);
                } else if (e.key === 'ArrowRight') {
                    updateModalSlider(currentImageIndex + 1);
                }
            }
        });

        // Gestione click sulle card dei prodotti
        if (productCards.length > 0) {
            productCards.forEach(card => {
                // Aggiungi evento click all'intera card (tranne il pulsante "Scopri di più")
                card.addEventListener('click', function(e) {
                    // Evita di aprire la modale se si clicca sul pulsante
                    if (e.target.closest('.view-details-btn')) {
                        return;
                    }

                    const productIndex = parseInt(this.dataset.index);
                    if (!isNaN(productIndex) && window.prodottiData && window.prodottiData[productIndex]) {
                        window.openProductModal(window.prodottiData[productIndex]);
                    }
                });
            });
        }

        // Gestione click sui pulsanti "Scopri di più"
        if (detailBtns.length > 0) {
            detailBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Evita la propagazione dell'evento alla card

                    // Prendi l'indice del prodotto dal data-attribute
                    const productIndex = parseInt(this.dataset.productIndex);
                    if (!isNaN(productIndex) && window.prodottiData && window.prodottiData[productIndex]) {
                        window.openProductModal(window.prodottiData[productIndex]);
                    }
                });
            });
        }
    }

    // Altre funzionalità varie
    function initMiscFeatures() {
        // Aggiungi stile CSS per le transizioni di pagina
        addPageTransitionStyle();

        // Gestione dell'oscuramento controllato dello sfondo quando la modale è aperta
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Tab' && document.body.classList.contains('modal-open')) {
                // Limita il tab focus agli elementi della modale
                const focusableElements = document.querySelector('.modal-content')
                    .querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');

                if (focusableElements.length > 0) {
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];

                    if (e.shiftKey && document.activeElement === firstElement) {
                        e.preventDefault();
                        lastElement.focus();
                    } else if (!e.shiftKey && document.activeElement === lastElement) {
                        e.preventDefault();
                        firstElement.focus();
                    }
                }
            }
        });

        // Gestione errori immagini
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('error', function () {
                if (!this.src.includes('placeholder')) {
                    console.warn('Errore caricamento immagine:', this.src);
                    this.src = '../images/placeholder-product.jpg';
                }
            });
        });
    }

    // Aggiungi stile al body durante la transizione di pagina
    function addPageTransitionStyle() {
        // Crea un elemento style se non esiste già
        if (!document.getElementById('page-transition-style')) {
            const style = document.createElement('style');
            style.id = 'page-transition-style';
            style.textContent = `
                body.loading-transition {
                    opacity: 0.7;
                    transition: opacity 0.3s ease;
                }
                
                body.variety-selected .products-title {
                    color: var(--primary-color);
                    transition: color 0.5s ease;
                }
                
                body.variety-selected .products-header::after {
                    background: var(--primary-color);
                    width: 150px;
                    transition: width 0.5s ease, background-color 0.5s ease;
                }
            `;
            document.head.appendChild(style);
        }
    }

    console.log("Inizializzazione script prodotti completata");
});

// Funzione per caricare le foto aggiuntive e gestire lo slider
function loadAdditionalPhotos(productId, mainPhotoFilename) {
    // Reset dei controlli slider
    const sliderControls = document.querySelector('.slider-controls');
    sliderControls.innerHTML = '';

    // Aggiungi un puntino per l'immagine principale
    const mainDot = document.createElement('button');
    mainDot.className = 'slider-dot active';
    mainDot.dataset.index = 0;
    sliderControls.appendChild(mainDot);

    // Nascondi i controlli fino a quando non sappiamo se ci sono foto aggiuntive
    sliderControls.style.display = 'none';

    // Crea array delle immagini che conterrà principale + aggiuntive
    const images = [{
        src: 'admin/uploads/piante/' + mainPhotoFilename
    }];

    // Effettua la chiamata AJAX per ottenere le foto aggiuntive
    fetch('get-additional-photos.php?product_id=' + productId)
        .then(response => response.json())
        .then(data => {
            if (data.photos && data.photos.length > 0) {
                // Aggiungi le foto aggiuntive all'array immagini
                data.photos.forEach((photo, index) => {
                    images.push({
                        src: 'admin/uploads/piante/' + photo.nome_file
                    });

                    // Crea un puntino per questa foto
                    const dot = document.createElement('button');
                    dot.className = 'slider-dot';
                    dot.dataset.index = index + 1; // +1 perché l'indice 0 è l'immagine principale
                    sliderControls.appendChild(dot);
                });

                // Mostra i controlli solo se abbiamo più di un'immagine
                if (images.length > 1) {
                    sliderControls.style.display = 'flex';

                    // Aggiungi evento click a tutti i puntini
                    document.querySelectorAll('.slider-dot').forEach(dot => {
                        dot.addEventListener('click', function() {
                            const imageIndex = parseInt(this.dataset.index);
                            showSlide(imageIndex);
                        });
                    });

                    // Aggiungi controlli tastiera per l'accessibilità
                    document.addEventListener('keydown', function(e) {
                        if (document.getElementById('product-modal').classList.contains('active')) {
                            const currentIndex = parseInt(document.querySelector('.slider-dot.active').dataset.index);

                            if (e.key === 'ArrowRight') {
                                // Freccia destra: prossima immagine
                                const nextIndex = Math.min(currentIndex + 1, images.length - 1);
                                showSlide(nextIndex);
                            } else if (e.key === 'ArrowLeft') {
                                // Freccia sinistra: immagine precedente
                                const prevIndex = Math.max(currentIndex - 1, 0);
                                showSlide(prevIndex);
                            }
                        }
                    });
                }
            }
        })
        .catch(error => {
            console.error('Errore nel caricamento delle foto aggiuntive:', error);
        });

    // Funzione per mostrare una specifica foto
    function showSlide(index) {
        if (index >= 0 && index < images.length) {
            // Aggiorna l'immagine visualizzata
            const modalImage = document.getElementById('modal-image');
            modalImage.src = images[index].src;
            modalImage.classList.add('fade-in');

            // Rimuovi la classe di animazione dopo che è terminata
            setTimeout(() => {
                modalImage.classList.remove('fade-in');
            }, 300);

            // Aggiorna i puntini attivi
            document.querySelectorAll('.slider-dot').forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });
        }
    }
}