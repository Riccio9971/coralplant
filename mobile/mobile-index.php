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
            height: 60vh;
            background: url('../images/azienda.jpeg') center/cover no-repeat;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(85, 126, 52, 0.6));
        }

        .hero-content {
            position: relative;
            z-index: 2;
            padding: 20px;
        }

        .hero-title {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero-subtitle {
            font-size: 1.2rem;
            opacity: 0.95;
            margin-bottom: 20px;
        }

        .hero-button {
            background: var(--primary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
        }

        .hero-button:active {
            transform: scale(0.98);
        }

        /* Section Styles */
        .section {
            padding: 40px 20px;
        }

        .section-title {
            font-size: 1.8rem;
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 25px;
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
            margin: 10px auto 0;
        }

        /* Timeline Chi Siamo */
        .timeline {
            position: relative;
            margin: 30px 0;
        }

        .timeline-item {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
        }

        .timeline-item h3 {
            color: var(--accent-color);
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .timeline-item p {
            color: var(--text-dark);
            line-height: 1.6;
            text-align: justify;
        }

        /* Contatti Section */
        .contact-grid {
            display: grid;
            gap: 15px;
            margin-top: 25px;
        }

        .contact-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .contact-card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .contact-card h3 {
            color: var(--accent-color);
            font-size: 1.1rem;
            margin-bottom: 8px;
        }

        .contact-card p {
            color: var(--text-dark);
            line-height: 1.5;
        }

        .contact-card a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .contact-card a:active {
            opacity: 0.7;
        }

        /* Map */
        .map-container {
            margin-top: 25px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .map-container iframe {
            width: 100%;
            height: 300px;
            border: none;
        }

        /* Social Links */
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .social-links a {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            text-decoration: none;
            box-shadow: var(--shadow);
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

        /* P.IVA Info */
        .piva-info {
            text-align: center;
            padding: 20px;
            background: var(--beige-light);
            border-radius: 12px;
            margin: 20px 0;
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .piva-info strong {
            color: var(--accent-color);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Coral Plant</h1>
            <p class="hero-subtitle">Giovani Piante da Reddito</p>
            <a href="mobile-prodotti.php" class="hero-button">
                <i class="fas fa-leaf"></i> Esplora i Prodotti
            </a>
        </div>
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

        <!-- P.IVA Info -->
        <div class="piva-info">
            <strong>Coral Plant Srl di Angelo D'apuzzo</strong><br>
            P.IVA: 09087111218<br>
            Torre del Greco (NA), Italia
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

            <div class="contact-card">
                <i class="fas fa-map-marker-alt"></i>
                <h3>Indirizzo</h3>
                <p>Torre del Greco (NA)<br>Campania, Italia</p>
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

        <!-- Mappa -->
        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.1!2d14.37!3d40.78!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQ2JzQ4LjAiTiAxNMKwMjInMTIuMCJF!5e0!3m2!1sit!2sit!4v1234567890"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

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
        Coral Plant Srl | P.IVA: 09087111218<br>
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
        document.querySelectorAll('a, button').forEach(element => {
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
