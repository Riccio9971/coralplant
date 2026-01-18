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

    // 6. Gestione browser back/forward button
    window.addEventListener('popstate', function(event) {
        if (event.state) {
            // Ricarica i prodotti in base allo stato salvato
            const url = window.location.href;
            loadProductsAjax(url);
        }
    });

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

    // Funzione per migliorare l'interattività della sidebar con caricamento AJAX
    function initSidebarEnhancements() {
        const sidebar = document.querySelector('.sidebar');

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

                // Altrimenti carica i prodotti via AJAX
                e.preventDefault();
                const url = this.getAttribute('data-url');
                if (url) {
                    loadProductsAjax(url);

                    // Feedback tattile su mobile
                    if ('vibrate' in navigator) {
                        navigator.vibrate(20);
                    }
                }
            });
        });

        // Gestione click su elemento varietà (intera area)
        document.querySelectorAll('.filter-item').forEach(item => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('data-url');
                if (url) {
                    loadProductsAjax(url);

                    // Feedback tattile su mobile
                    if ('vibrate' in navigator) {
                        navigator.vibrate(25);
                    }
                }
            });
        });

        // Gestione click su "Visualizza catalogo completo"
        const viewAllLink = document.querySelector('.view-all-filters');
        if (viewAllLink) {
            viewAllLink.addEventListener('click', function (e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                if (url) {
                    loadProductsAjax(url);
                }
            });
        }

        // Inizializza lo stato della sidebar basato sui parametri URL
        initSidebarState();
    }

    // Funzione per inizializzare lo stato della sidebar basato sull'URL
    function initSidebarState() {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('varieta')) {
            document.body.classList.add('variety-selected');

            // Evidenzia visualmente l'elemento della varietà selezionata
            const varietyId = urlParams.get('varieta');
            const selectedItem = document.querySelector(`.filter-item[data-variety-id="${varietyId}"]`);
            if (selectedItem) {
                selectedItem.classList.add('active');

                // Assicurati che il genitore (lista delle varietà) sia espanso
                const parentList = selectedItem.closest('.filter-items');
                if (parentList) {
                    parentList.classList.add('expanded');

                    // Assicurati che l'icona della categoria sia espansa
                    const categoryIcon = parentList.previousElementSibling?.querySelector('.filter-icon');
                    if (categoryIcon) {
                        categoryIcon.classList.add('expanded');
                    }

                    // Assicurati che la categoria sia marcata come attiva
                    const category = parentList.previousElementSibling;
                    if (category) {
                        category.classList.add('active');
                    }
                }
            }
        }

        // Gestisci anche il caso in cui sia selezionata solo la specie
        if (urlParams.has('specie') && !urlParams.has('varieta')) {
            const specieId = urlParams.get('specie');
            const specieCategory = document.querySelector(`.filter-category[data-url*="specie=${specieId}"]`);

            if (specieCategory) {
                specieCategory.classList.add('active');

                // Espandi le varietà di questa specie
                const filterItems = specieCategory.nextElementSibling;
                const filterIcon = specieCategory.querySelector('.filter-icon');
                if (filterItems) {
                    filterItems.classList.add('expanded');
                }
                if (filterIcon) {
                    filterIcon.classList.add('expanded');
                }
            }
        }

        // Gestisci show_all
        if (urlParams.has('show_all')) {
            const viewAllLink = document.querySelector('.view-all-filters');
            if (viewAllLink) {
                viewAllLink.classList.add('active');
            }
        }
    }

    // Funzione per caricare i prodotti via AJAX
    async function loadProductsAjax(url) {
        try {
            // Mostra indicatore di caricamento
            document.body.classList.add('loading-transition');
            const productsContainer = document.querySelector('.products-container');
            if (productsContainer) {
                productsContainer.style.opacity = '0.5';
                productsContainer.style.pointerEvents = 'none';
            }

            // Estrai i parametri dall'URL
            const urlObj = new URL(url, window.location.origin);
            const params = urlObj.searchParams;

            // Costruisci l'URL per l'endpoint AJAX
            const ajaxUrl = 'load-products-ajax.php?' + params.toString();

            // Effettua la richiesta AJAX
            const response = await fetch(ajaxUrl);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Errore nel caricamento dei dati');
            }

            // Aggiorna il contenuto della pagina
            updatePageContent(data);

            // Aggiorna le classi active nella sidebar
            updateActiveFilters(data.filters);

            // Aggiorna l'URL del browser senza ricaricare la pagina
            history.pushState(data.filters, '', url);

            // Rimuovi l'indicatore di caricamento
            if (productsContainer) {
                productsContainer.style.opacity = '1';
                productsContainer.style.pointerEvents = 'auto';
            }
            document.body.classList.remove('loading-transition');

        } catch (error) {
            console.error('Errore nel caricamento dei prodotti:', error);

            // In caso di errore, fallback alla navigazione normale
            window.location.href = url;
        }
    }

    // Funzione per aggiornare il contenuto della pagina
    function updatePageContent(data) {
        const productsHeader = document.querySelector('.products-header');
        const productsGrid = document.querySelector('.products-grid');
        const productsContainer = document.querySelector('.products-container');

        if (!productsContainer) return;

        // Aggiorna titolo e sottotitolo
        if (productsHeader) {
            const title = productsHeader.querySelector('.products-title');
            const subtitle = productsHeader.querySelector('.products-subtitle');

            if (title) title.textContent = data.title;
            if (subtitle) subtitle.textContent = data.subtitle;
        }

        // Rimuovi contenuto esistente (tranne header)
        const existingGrid = productsContainer.querySelector('.products-grid, .no-products');
        if (existingGrid) {
            existingGrid.remove();
        }

        // Renderizza il nuovo contenuto in base al tipo
        let newContent;
        if (data.type === 'specie') {
            newContent = renderSpecieCards(data.data);
        } else if (data.type === 'varieta') {
            newContent = renderVarietaCards(data.data);
        } else if (data.type === 'prodotti') {
            newContent = renderProductCards(data.data);
        }

        // Aggiungi il nuovo contenuto al container
        if (newContent) {
            productsContainer.appendChild(newContent);
        }

        // Re-inizializza gli event listener per le nuove card
        initCardAnimations();
        initProductModal();
    }

    // Funzione per renderizzare le card delle specie
    function renderSpecieCards(specie) {
        if (!specie || specie.length === 0) {
            return createNoProductsMessage('Nessuna categoria trovata', 'Non ci sono categorie disponibili.');
        }

        const grid = document.createElement('div');
        grid.className = 'products-grid';

        specie.forEach((s, index) => {
            const card = document.createElement('div');
            card.className = 'product-card species-card';
            card.dataset.index = index;
            card.onclick = () => loadProductsAjax(`prodotti.php?specie=${s.id}`);

            const imagePath = s.immagine ? `admin/uploads/piante/${s.immagine}` : null;

            card.innerHTML = `
                <div class="product-image-container">
                    ${imagePath ?
                        `<img src="${imagePath}" alt="${escapeHtml(s.nome)}" class="product-image" onerror="this.parentElement.innerHTML='<div class=\\'placeholder-image\\'><i class=\\'fas fa-leaf\\' style=\\'font-size: 4rem; color: var(--olivine);\\'></i></div>'">` :
                        `<div class="placeholder-image"><i class="fas fa-leaf" style="font-size: 4rem; color: var(--olivine);"></i></div>`
                    }
                </div>
                <div class="product-info">
                    <h3 class="product-title">${escapeHtml(s.nome)}</h3>
                    <p class="product-category">
                        <i class="fas fa-leaf"></i>
                        Specie
                    </p>
                    <p class="product-description">
                        ${s.num_prodotti} prodott${s.num_prodotti == 1 ? 'o' : 'i'} disponibil${s.num_prodotti == 1 ? 'e' : 'i'}
                    </p>
                    <div class="product-action">
                        <button class="view-details-btn">
                            <i class="fas fa-arrow-right"></i> Vedi varietà
                        </button>
                    </div>
                </div>
            `;

            grid.appendChild(card);
        });

        return grid;
    }

    // Funzione per renderizzare le card delle varietà
    function renderVarietaCards(varieta) {
        if (!varieta || varieta.length === 0) {
            return createNoProductsMessage(
                'Nessuna varietà trovata',
                'Non ci sono varietà disponibili per questa specie.',
                'prodotti.php',
                'Torna a tutte le categorie'
            );
        }

        const grid = document.createElement('div');
        grid.className = 'products-grid';

        varieta.forEach((v, index) => {
            const card = document.createElement('div');
            card.className = 'product-card variety-card';
            card.dataset.index = index;
            card.onclick = () => loadProductsAjax(`prodotti.php?varieta=${v.id}`);

            const imagePath = v.immagine ? `admin/uploads/piante/${v.immagine}` : null;

            card.innerHTML = `
                <div class="product-image-container">
                    ${imagePath ?
                        `<img src="${imagePath}" alt="${escapeHtml(v.nome)}" class="product-image" onerror="this.parentElement.innerHTML='<div class=\\'placeholder-image\\'><i class=\\'fas fa-leaf\\' style=\\'font-size: 4rem; color: var(--olivine);\\'></i></div>'">` :
                        `<div class="placeholder-image"><i class="fas fa-leaf" style="font-size: 4rem; color: var(--olivine);"></i></div>`
                    }
                </div>
                <div class="product-info">
                    <h3 class="product-title">${escapeHtml(v.nome)}</h3>
                    <p class="product-category">
                        <i class="fas fa-spa"></i>
                        Varietà
                    </p>
                    <p class="product-description">
                        ${v.num_prodotti} prodott${v.num_prodotti == 1 ? 'o' : 'i'} disponibil${v.num_prodotti == 1 ? 'e' : 'i'}
                    </p>
                    <div class="product-action">
                        <button class="view-details-btn">
                            <i class="fas fa-arrow-right"></i> Vedi prodotti
                        </button>
                    </div>
                </div>
            `;

            grid.appendChild(card);
        });

        return grid;
    }

    // Funzione per renderizzare le card dei prodotti
    function renderProductCards(prodotti) {
        if (!prodotti || prodotti.length === 0) {
            return createNoProductsMessage(
                'Nessun prodotto trovato',
                'Al momento non ci sono prodotti disponibili per questa selezione.',
                'prodotti.php',
                'Torna a tutte le categorie'
            );
        }

        const grid = document.createElement('div');
        grid.className = 'products-grid';

        // Salva i dati dei prodotti in una variabile globale per la modale
        window.prodottiData = prodotti;

        prodotti.forEach((p, index) => {
            const card = document.createElement('div');
            card.className = 'product-card';
            card.dataset.index = index;

            const imagePath = `admin/uploads/piante/${p.nome_file}`;
            const description = p.descrizione.length > 150 ?
                p.descrizione.substring(0, 150) + '...' :
                p.descrizione;

            card.innerHTML = `
                <div class="product-image-container">
                    <img src="${imagePath}" alt="${escapeHtml(p.titolo)}" class="product-image" onerror="this.src='../images/placeholder-product.jpg'">
                </div>
                <div class="product-info">
                    <h3 class="product-title">${escapeHtml(p.titolo)}</h3>
                    <p class="product-category">
                        <i class="fas fa-seedling"></i>
                        ${escapeHtml(p.specie_nome)} &gt; ${escapeHtml(p.varieta_nome)}
                    </p>
                    <p class="product-description">${escapeHtml(description)}</p>
                    <div class="product-action">
                        <button class="view-details-btn" data-product-index="${index}">
                            <i class="fas fa-search-plus"></i> Scopri di più
                        </button>
                    </div>
                </div>
            `;

            grid.appendChild(card);
        });

        return grid;
    }

    // Funzione per creare il messaggio "nessun prodotto"
    function createNoProductsMessage(title, message, linkUrl = null, linkText = null) {
        const div = document.createElement('div');
        div.className = 'no-products';

        let innerHTML = `
            <h3>${escapeHtml(title)}</h3>
            <p>${escapeHtml(message)}</p>
        `;

        if (linkUrl && linkText) {
            innerHTML += `<a href="${linkUrl}" class="back-btn" onclick="event.preventDefault(); loadProductsAjax('${linkUrl}');"><i class="fas fa-arrow-left"></i> ${escapeHtml(linkText)}</a>`;
        }

        div.innerHTML = innerHTML;
        return div;
    }

    // Funzione per aggiornare le classi active nella sidebar
    function updateActiveFilters(filters) {
        // Rimuovi tutte le classi active esistenti
        document.querySelectorAll('.filter-category.active, .filter-item.active, .view-all-filters.active').forEach(el => {
            el.classList.remove('active');
        });

        // Chiudi tutte le liste di varietà
        document.querySelectorAll('.filter-items.expanded').forEach(items => {
            items.classList.remove('expanded');
        });
        document.querySelectorAll('.filter-icon.expanded').forEach(icon => {
            icon.classList.remove('expanded');
        });

        // Aggiorna in base ai nuovi filtri
        if (filters.show_all) {
            const viewAllLink = document.querySelector('.view-all-filters');
            if (viewAllLink) {
                viewAllLink.classList.add('active');
            }
            document.body.classList.remove('variety-selected');
        } else if (filters.varieta > 0) {
            // Seleziona la varietà
            const varietaItem = document.querySelector(`.filter-item[data-variety-id="${filters.varieta}"]`);
            if (varietaItem) {
                varietaItem.classList.add('active');

                // Espandi la lista delle varietà
                const parentList = varietaItem.closest('.filter-items');
                const category = parentList?.previousElementSibling;
                const icon = category?.querySelector('.filter-icon');

                if (parentList) parentList.classList.add('expanded');
                if (category) category.classList.add('active');
                if (icon) icon.classList.add('expanded');
            }
            document.body.classList.add('variety-selected');
        } else if (filters.specie > 0) {
            // Seleziona la specie
            const specieCategory = document.querySelector(`.filter-category[data-url*="specie=${filters.specie}"]`);
            if (specieCategory) {
                specieCategory.classList.add('active');

                // Espandi la lista delle varietà
                const filterItems = specieCategory.nextElementSibling;
                const filterIcon = specieCategory.querySelector('.filter-icon');

                if (filterItems) filterItems.classList.add('expanded');
                if (filterIcon) filterIcon.classList.add('expanded');
            }
            document.body.classList.remove('variety-selected');
        } else {
            document.body.classList.remove('variety-selected');
        }
    }

    // Funzione helper per escapare HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
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