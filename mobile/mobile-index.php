<?php
/**
 * Pagina Index mobile per Coral Plant
 */

// Impostazioni della pagina
$page_title = 'Coral Plant - Innovazione & Tradizione';
$is_mobile = true;

// Include la configurazione del database
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $page_title; ?></title>

    <!-- Importazione del font Varela Round da Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="mobile-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Stili per impedire lo scorrimento orizzontale */
        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            padding-bottom: 95px; /* Spazio per il footer fisso + footer legale */
            background: linear-gradient(to top,
                rgba(245, 245, 230, 0.25),
                rgba(179, 213, 155, 0.15),
                rgba(182, 220, 172, 0.23));
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Hero Section */
        .hero-section {
            height: 70vh;
            background: linear-gradient(rgba(0,0,0,0.3), rgba(85, 126, 52, 0.5)),
                        url('../images/azienda.jpeg') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }

        .hero-content {
            padding: 20px;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero-title {
            font-size: 2.8rem;
            margin-bottom: 15px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            opacity: 0.95;
            margin-bottom: 25px;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
        }

        .hero-button {
            background: var(--primary-color);
            color: white;
            padding: 15px 35px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(0,0,0,0.4);
            transition: all 0.3s ease;
        }

        .hero-button:active {
            transform: translateY(2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.3);
        }

        /* Section Styles */
        .section {
            padding: 50px 20px;
        }

        .section-title {
            font-size: 2rem;
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            font-weight: 700;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--primary-color);
            margin: 15px auto 0;
            border-radius: 2px;
        }

        /* Prodotti Carousel */
        .products-carousel {
            position: relative;
            overflow: hidden;
            margin: 30px 0;
        }

        .products-track {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 10px 5px;
        }

        .products-track::-webkit-scrollbar {
            display: none;
        }

        .product-card-mini {
            min-width: 280px;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            scroll-snap-align: center;
            transition: transform 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .product-card-mini:active {
            transform: scale(0.98);
        }

        /* Rimuovi stili link dalle card mini */
        a.product-card-mini,
        a.product-card-mini:visited,
        a.product-card-mini:active,
        a.product-card-mini:focus {
            text-decoration: none;
            color: inherit;
        }

        .product-card-mini img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .product-card-mini h3 {
            padding: 15px;
            color: var(--accent-color);
            font-size: 1.1rem;
            text-align: center;
        }

        .view-all-products {
            display: block;
            text-align: center;
            margin: 25px auto 0;
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            width: fit-content;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .view-all-products:active {
            transform: scale(0.98);
        }

        /* Timeline Chi Siamo - Animata */
        .timeline {
            position: relative;
            margin: 30px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
        }

        .timeline-item {
            background: white;
            border-radius: 15px;
            padding: 20px 20px 20px 50px;
            margin-bottom: 25px;
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            opacity: 0;
            animation: slideInLeft 0.6s ease-out forwards;
        }

        .timeline-item:nth-child(1) { animation-delay: 0.1s; }
        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.3s; }
        .timeline-item:nth-child(4) { animation-delay: 0.4s; }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 25px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
            box-shadow: 0 0 0 3px var(--primary-color);
        }

        .timeline-item h3 {
            color: var(--accent-color);
            font-size: 1.3rem;
            margin-bottom: 12px;
            font-weight: 700;
        }

        .timeline-item p {
            color: var(--text-dark);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* Contatti Section */
        .contact-grid {
            display: grid;
            gap: 20px;
            margin-top: 30px;
        }

        .contact-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .contact-card:active {
            transform: translateY(-5px);
        }

        .contact-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
            display: block;
        }

        .contact-card h3 {
            color: var(--accent-color);
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .contact-card p {
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .contact-card a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-card a:active {
            opacity: 0.7;
        }

        /* Social Links */
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 25px;
        }

        .social-links a {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .social-links a:active {
            transform: scale(0.95);
        }

        /* Footer fisso in fondo alla pagina */
        .footer-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: var(--footer-height);
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 900;
            display: flex;
            justify-content: space-around;
            border-top: 1px solid var(--beige);
        }

        /* Footer legale discreto - posizionato sopra il footer di navigazione */
        .legal-footer-mobile {
            position: fixed;
            bottom: var(--footer-height);
            left: 0;
            width: 100%;
            background: rgba(85, 126, 52, 0.95);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 0.65rem;
            line-height: 1.4;
            z-index: 850;
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
        }

        .legal-footer-mobile a {
            color: white;
            text-decoration: none;
            opacity: 0.95;
            padding: 0 6px;
            font-weight: 500;
        }

        .legal-footer-mobile a:active {
            opacity: 0.7;
        }

        .legal-footer-mobile .separator {
            opacity: 0.7;
            padding: 0 3px;
        }

        .footer-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-light);
            padding: 8px 0;
            flex: 1;
            font-size: 0.8rem;
        }

        .footer-link i {
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .footer-link.active {
            color: var(--primary-color);
        }

        /* Company Info in fondo */
        .company-info-bottom {
            background: var(--beige-light);
            padding: 20px;
            text-align: center;
            margin-top: 40px;
            border-top: 3px solid var(--primary-color);
        }

        .company-info-bottom p {
            color: var(--text-dark);
            margin: 5px 0;
            font-size: 0.9rem;
        }

        .company-info-bottom strong {
            color: var(--accent-color);
            font-size: 1rem;
        }

        /* Animazioni */
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

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Coral Plant</h1>
            <p class="hero-subtitle">Giovani Piante da Reddito</p>
            <a href="mobile-prodotti.php" class="hero-button">
                <i class="fas fa-leaf"></i> Esplora i Prodotti
            </a>
        </div>
    </section>

    <!-- Sezione Prodotti -->
    <section id="prodotti" class="section">
        <h2 class="section-title">I Nostri Prodotti</h2>

        <div class="products-carousel">
            <div class="products-track">
                <a href="mobile-prodotti.php?specie=28" class="product-card-mini">
                    <img src="../images/Violacciocca.jpeg" alt="Violacciocca">
                    <h3>Violacciocca</h3>
                </a>
                <a href="mobile-prodotti.php?specie=6" class="product-card-mini">
                    <img src="../images/Bocca di leone.jpeg" alt="Bocche di leone">
                    <h3>Bocche di leone</h3>
                </a>
                <a href="mobile-prodotti.php?specie=17" class="product-card-mini">
                    <img src="../images/Helianthus.jpeg" alt="Helianthus">
                    <h3>Helianthus</h3>
                </a>
                <a href="mobile-prodotti.php?specie=14" class="product-card-mini">
                    <img src="../images/Delphinium.jpeg" alt="Delphinium">
                    <h3>Delphinium</h3>
                </a>
                <a href="mobile-prodotti.php?specie=11" class="product-card-mini">
                    <img src="../images/Celosia.jpeg" alt="Celosia">
                    <h3>Celosia</h3>
                </a>
                <a href="mobile-prodotti.php?specie=7" class="product-card-mini">
                    <img src="../images/Brassica.jpeg" alt="Brassica">
                    <h3>Brassica</h3>
                </a>
            </div>
        </div>

        <a href="mobile-prodotti.php" class="view-all-products">
            <i class="fas fa-th-large"></i> Vedi Tutti i Prodotti
        </a>
    </section>

    <!-- Sezione Chi Siamo -->
    <section id="chi-siamo" class="section">
        <h2 class="section-title">La Nostra Storia</h2>

        <div class="timeline">
            <div class="timeline-item">
                <h3>1994 - FONDAZIONE</h3>
                <p>Nel 1994, a Torre del Greco, l'azienda nasce come una giovane realtà produttiva di fiore reciso. Una storia fatta di passione e attaccamento alle proprie radici che, ha fatto della produzione agricola il centro della propria esistenza.</p>
            </div>

            <div class="timeline-item">
                <h3>2009 - INNOVAZIONE</h3>
                <p>Nel 2009, si ha la conversione in vivaio ortoflorovivaistico, introducendo strutture moderne che hanno permesso una coltivazione più efficiente e di alta qualità.</p>
            </div>

            <div class="timeline-item">
                <h3>2016 - ESPANSIONE</h3>
                <p>Nel 2016, la Coral Plant srl incrementa la sua attività grazie ad una grande espansione, traguardo che ha permesso di raggiungere nuovi mercati e clienti.</p>
            </div>

            <div class="timeline-item">
                <h3>2025 - OGGI</h3>
                <p>Oggi, leader del settore, la Coral Plant srl vanta di una grande superficie, di circa 4,5 ettari; uno staff professionale e qualificato; collaborazioni e vendite dirette con ditte nazionali ed estere.</p>
            </div>
        </div>
    </section>

    <!-- Sezione Contatti -->
    <section id="contatti" class="section">
        <h2 class="section-title">Contattaci</h2>

        <div class="contact-grid">
            <div class="contact-card">
                <i class="fas fa-phone"></i>
                <h3>Telefono</h3>
                <p><a href="tel:0818831702">081 883 17 02</a></p>
            </div>

            <div class="contact-card">
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p><a href="mailto:info@coralplant.it">info@coralplant.it</a></p>
            </div>

            <div class="contact-card">
                <i class="fas fa-clock"></i>
                <h3>Orari di Apertura</h3>
                <p>
                    Lun-Ven: 07:30-13:00 / 15:00-17:00<br>
                    Sab: 07:30-13:30<br>
                    Dom: Chiuso
                </p>
            </div>
        </div>

        <!-- Social Links -->
        <div class="social-links">
            <a href="https://www.facebook.com/p/Azienda-Agricola-Coral-Plant-100063724861260/" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/coral.plant/" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </section>

    <!-- Company Info in Fondo -->
    <div class="company-info-bottom">
        <p><strong>Coral Plant Srl di Angelo D'apuzzo</strong></p>
        <p>P.IVA: 09087111218</p>
        <p>Torre del Greco (NA), Italia</p>
        <p>&copy; <?php echo date('Y'); ?> Tutti i diritti riservati</p>
    </div>

    <!-- Footer Mobile -->
    <div class="footer-nav">
        <a href="#" class="footer-link active" data-section="home">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="mobile-prodotti.php" class="footer-link">
            <i class="fas fa-leaf"></i>
            <span>Prodotti</span>
        </a>
        <a href="#chi-siamo" class="footer-link" data-section="chi-siamo">
            <i class="fas fa-users"></i>
            <span>Chi Siamo</span>
        </a>
        <a href="#contatti" class="footer-link" data-section="contatti">
            <i class="fas fa-envelope"></i>
            <span>Contatti</span>
        </a>
    </div>

    <!-- Footer legale discreto -->
    <div class="legal-footer-mobile">
        <a href="mobile-privacy.php">Privacy Policy</a>
        <span class="separator">•</span>
        <a href="mobile-termini.php">Termini e Condizioni</a>
    </div>

    <!-- Script per gestire le icone attive nel footer -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestione icone attive nel footer in base allo scroll e hash
        const footerLinks = document.querySelectorAll('.footer-link[data-section]');
        const sections = document.querySelectorAll('.section[id]');

        function setActiveFooterLink(sectionId) {
            footerLinks.forEach(link => {
                const linkSection = link.getAttribute('data-section');
                if (linkSection === sectionId || (sectionId === 'home' && linkSection === 'home')) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Controlla hash all'avvio
        function checkHash() {
            const hash = window.location.hash.substring(1);
            if (hash) {
                setActiveFooterLink(hash);
            } else {
                setActiveFooterLink('home');
            }
        }

        // Osserva lo scroll per attivare le sezioni
        const observerOptions = {
            root: null,
            rootMargin: '-50% 0px -50% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.getAttribute('id');
                    setActiveFooterLink(sectionId);
                    // Aggiorna l'hash senza scroll
                    if (history.replaceState) {
                        history.replaceState(null, null, '#' + sectionId);
                    }
                }
            });
        }, observerOptions);

        // Osserva tutte le sezioni
        sections.forEach(section => {
            observer.observe(section);
        });

        // Gestisci click sui link del footer
        footerLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.startsWith('#')) {
                    e.preventDefault();
                    const targetId = href.substring(1);
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth' });
                        setActiveFooterLink(targetId);
                    } else if (href === '#') {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        setActiveFooterLink('home');
                    }
                }
            });
        });

        // Controlla l'hash al caricamento
        checkHash();

        // Ascolta i cambiamenti di hash
        window.addEventListener('hashchange', checkHash);

        // Feedback tattile per tutti i link
        document.querySelectorAll('a, button, .product-card-mini, .contact-card').forEach(element => {
            element.addEventListener('touchstart', function() {
                if (navigator.vibrate) {
                    navigator.vibrate(10);
                }
            });
        });
    });
    </script>
</body>
</html>
