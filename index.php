<?php
// Include la configurazione del database
require_once 'config.php';

// Funzione per rilevare dispositivi mobili
function is_mobile() {
    $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    
    $mobile_agents = [
        'Android', 'iPhone', 'iPod', 'iPad', 'Windows Phone', 'BlackBerry', 
        'Opera Mini', 'Mobile', 'Kindle', 'Silk', 'webOS', 'Palm', 'Symbian'
    ];
    
    foreach ($mobile_agents as $device) {
        if (stripos($user_agent, $device) !== false) {
            return true;
        }
    }
    
    return false;
}

// Determina se l'utente ha richiesto esplicitamente una versione
$requested_view = isset($_GET['view']) ? $_GET['view'] : '';

// Ottieni il nome del file corrente
$current_page = basename($_SERVER['PHP_SELF']);

// Se l'utente ha richiesto esplicitamente una versione
if ($requested_view == 'mobile') {
    // Reindirizza alla versione mobile se non ci siamo già
    if (strpos($current_page, 'mobile-') !== 0) {
        $mobile_page = 'mobile/mobile-' . $current_page;
        header("Location: $mobile_page");
        exit;
    }
} elseif ($requested_view == 'desktop') {
    // Reindirizza alla versione desktop se siamo nella mobile
    if (strpos($current_page, 'mobile-') === 0) {
        $desktop_page = substr($current_page, 7); // Rimuove 'mobile-'
        header("Location: $desktop_page");
        exit;
    }
} else {
    // Nessuna preferenza esplicita, usa il rilevamento del dispositivo
    if (is_mobile()) {
        // Se è un dispositivo mobile e siamo su una pagina desktop
        if (strpos($current_page, 'mobile-') !== 0) {
            $mobile_page = 'mobile/mobile-' . $current_page;
            header("Location: $mobile_page");
            exit;
        }
    } else {
        // Se è un dispositivo desktop e siamo su una pagina mobile
        if (strpos($current_page, 'mobile-') === 0) {
            $desktop_page = substr($current_page, 7); // Rimuove 'mobile-'
            header("Location: $desktop_page");
            exit;
        }
    }
}

// Impostazioni della pagina
$page_title = 'Coral Plant - Innovazione & Tradizione';
$additional_css = [];
$additional_js = [];

// Includi l'header
include 'web/header.php';
?>

<!-- Header con Parallax -->
<header class="header">
  <div class="parallax-bg" style="background-image: url('images/azienda.jpeg');"></div>
  <div class="header-content">
    <h1 class="fade-in-up">Coral Plant Srl</h1>
    <p class="fade-in-up">Giovani Piante da Reddito</p>
  </div>
</header>

<!-- Sezione Prodotti con card modificate -->
<section id="prodotti" class="section">
  <h2 class="section-title">I Nostri Prodotti</h2>
  <div class="carousel-container">
    <div class="carousel-track">
      <!-- Prodotti con card migliorate - SENZA DESCRIZIONE --> 
      <div class="carousel-item">
        <div class="product-card">
          <img src="images/Violacciocca.jpeg" alt="Violacciocca" class="product-image">
          <div class="product-info">
            <h3 class="product-title">Violacciocca</h3>
            <div class="product-badges">
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="product-card">
          <img src="images/Bocca di leone.jpeg" alt="Bocche di leone" class="product-image">
          <div class="product-info">
            <h3 class="product-title">Bocche di leone</h3>
            <div class="product-badges">
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="product-card">
          <img src="images/Helianthus.jpeg" alt="Helianthus" class="product-image">
          <div class="product-info">
            <h3 class="product-title">Helianthus</h3>
            <div class="product-badges">
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="product-card">
          <img src="images/Delphinium.jpeg" alt="Delphinium" class="product-image">
          <div class="product-info">
            <h3 class="product-title">Delphinium</h3>
            <div class="product-badges">
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="product-card">
          <img src="images/Celosia.jpeg" alt="Celosia" class="product-image">
          <div class="product-info">
            <h3 class="product-title">Celosia</h3>
            <div class="product-badges">
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-item">
        <div class="product-card">
          <img src="images/Brassica.jpeg" alt="Brassica" class="product-image">
          <div class="product-info">
            <h3 class="product-title">Brassica</h3>
            <div class="product-badges">
            </div>
          </div>
        </div>
      </div>
	  </div>
    <button class="carousel-button prev" aria-label="Precedente">&#10094;</button>
    <button class="carousel-button next" aria-label="Successivo">&#10095;</button>
  </div>
  
  <!-- Bottone "Tutti i Prodotti" -->
  <a href="prodotti.php" class="view-all-button">Tutti i Prodotti</a>
</section>

<!-- Timeline Storia -->
<section id="chi-siamo" class="section">
  <h2 class="section-title">La Nostra Storia</h2>
  <div class="timeline">
    <div class="timeline-item">
      <div class="timeline-content">
        <h3>1994 - FONDAZIONE</h3>
        <p>Nel 1994, a Torre del Greco, l'azienda nasce come una giovane realtà produttiva di fiore reciso. Una storia fatta di passione e attaccamento alle proprie radici che, ha fatto della produzione agricola il centro della propria esistenza.</p>
      </div>
    </div>
    <div class="timeline-item">
      <div class="timeline-content">
        <h3>2009 - INNOVAZIONE</h3>
        <p>Nel 2009, si ha la conversione in vivaio ortoflorovivaistico, introducendo strutture moderne che hanno permesso una coltivazione più efficiente e di alta qualità.</p>
      </div>
    </div>
    <div class="timeline-item">
      <div class="timeline-content">
        <h3>2016 - ESPANSIONE</h3>
        <p>Nel 2016, la Coral Plant srl incrementa la sua attività grazie ad una grande espansione, traguardo che ha permesso di raggiungere nuovi mercati e clienti.</p>
      </div>
    </div>
    <div class="timeline-item">
      <div class="timeline-content">
        <h3>2025 - OGGI</h3>
        <p>Oggi, leader del settore, la Coral Plant srl vanta di una grande superficie, di circa 4,5 ettari; uno staff professionale e qualificato; collaborazioni e vendite dirette con ditte nazionali ed estere.</p>
      </div>
    </div>
  </div>
</section>

<!-- Aggiungere questo script alla fine di index.php, prima della chiusura del tag </body> -->
<!-- Subito dopo l'inclusione di footer.php -->

<script>
// Script specifico per index.php
document.addEventListener('DOMContentLoaded', function() {
    console.log("Script specifico di index.php caricato");
    
    // Gestione carosello
    initCarousel();
    
    // Gestione timeline
    initTimeline();
    
    // Gestione della navbar con scroll
    initNavbar();
    
    // Funzione per gestire il carosello nella home page
    function initCarousel() {
        const carouselTrack = document.querySelector('.carousel-track');
        if (!carouselTrack) {
            console.log("Carosello non trovato");
            return;
        }

        const carouselItems = document.querySelectorAll('.carousel-item');
        if (carouselItems.length === 0) {
            console.log("Nessun elemento nel carosello");
            return;
        }

        const prevButton = document.querySelector('.carousel-button.prev');
        const nextButton = document.querySelector('.carousel-button.next');
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
            console.log("Spostamento carosello:", offset);

            carouselTrack.style.transform = `translateX(-${offset}px)`;
        }

        if (prevButton && nextButton) {
            prevButton.addEventListener('click', function() {
                console.log("Click su Prev");
                moveToSlide(currentIndex - 1);
            });
            
            nextButton.addEventListener('click', function() {
                console.log("Click su Next");
                moveToSlide(currentIndex + 1);
            });
        } else {
            console.log("Pulsanti prev/next non trovati");
        }

        // Aggiorna il carosello su resize
        window.addEventListener('resize', function() {
            updateItemsPerView();
            moveToSlide(currentIndex);
        });

        // Inizializza la visualizzazione corretta al caricamento
        updateItemsPerView();
        moveToSlide(0);
        console.log("Carosello inizializzato con successo");
    }

    // Funzione per gestire le animazioni della timeline
    function initTimeline() {
        const timelineItems = document.querySelectorAll('.timeline-item');
        if (timelineItems.length === 0) {
            console.log("Nessun elemento timeline trovato");
            return;
        }

        // Usa un semplice controllo di visibilità mediante scroll
        function checkTimelineVisibility() {
            timelineItems.forEach(item => {
                const itemTop = item.getBoundingClientRect().top;
                const itemBottom = item.getBoundingClientRect().bottom;
                const windowHeight = window.innerHeight;
                
                if (itemTop < windowHeight * 0.8 && itemBottom > 0) {
                    item.classList.add('visible');
                }
            });
        }

        // Controlla la visibilità all'inizio e durante lo scroll
        window.addEventListener('scroll', checkTimelineVisibility);
        checkTimelineVisibility();
        console.log("Timeline inizializzata con successo");
    }
    
    // Funzione per la navbar
    function initNavbar() {
        let prevScrollPos = window.pageYOffset;
        const navbar = document.querySelector('.nav-container');
        
        if (!navbar) {
            console.log("Navbar non trovata");
            return;
        }

        window.addEventListener('scroll', function() {
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
        });

        // Gestione del menu hamburger
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');

        if (hamburger && navLinks) {
            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });

            // Chiudi il menu quando si clicca su un link
            document.querySelectorAll('.nav-links a').forEach(link => {
                link.addEventListener('click', function() {
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                });
            });
        }
        
        console.log("Navbar inizializzata con successo");
    }
});
</script>

<?php
// Includi il footer
include 'web/footer.php';
?>