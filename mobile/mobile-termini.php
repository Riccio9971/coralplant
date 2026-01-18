<?php
/**
 * Pagina Termini e Condizioni mobile per Coral Plant
 */

// Impostazioni della pagina
$page_title = 'Termini e Condizioni - Coral Plant Mobile';
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
            padding-bottom: 100px; /* Spazio per il footer fisso */
            background: linear-gradient(to top,
                rgba(245, 245, 230, 0.25),
                rgba(179, 213, 155, 0.15),
                rgba(182, 220, 172, 0.23));
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Varela Round', 'Arial Rounded MT Bold', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header mobile */
        .mobile-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--white);
            box-shadow: var(--shadow);
            z-index: 1000;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .back-button {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--accent-color);
            cursor: pointer;
            padding: 5px;
        }

        .header-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-dark);
            flex: 1;
        }

        /* Contenuto legale */
        .legal-content-mobile {
            margin-top: 70px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .legal-title-mobile {
            font-size: 1.6rem;
            color: var(--accent-color);
            margin-bottom: 10px;
            text-align: center;
        }

        .legal-subtitle-mobile {
            text-align: center;
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .legal-content-mobile h2 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-top: 25px;
            margin-bottom: 15px;
        }

        .legal-content-mobile p {
            line-height: 1.7;
            margin-bottom: 15px;
            color: var(--text-dark);
            text-align: justify;
        }

        .legal-content-mobile ul {
            margin: 15px 0;
            padding-left: 20px;
        }

        .legal-content-mobile li {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .legal-content-mobile a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        .legal-content-mobile a:active {
            color: var(--accent-light);
        }

        /* Footer aziendale */
        .company-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: var(--accent-color);
            color: white;
            text-align: center;
            padding: 15px 20px;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 900;
        }

        .company-footer p {
            margin: 3px 0;
            font-size: 0.85rem;
            opacity: 0.95;
        }

        .company-footer .social-icons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
        }

        .company-footer .social-icons a {
            color: white;
            font-size: 1.3rem;
            transition: opacity 0.3s;
        }

        .company-footer .social-icons a:active {
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <!-- Header mobile -->
    <header class="mobile-header">
        <button class="back-button" onclick="window.history.back()">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="header-title">Termini e Condizioni</h1>
    </header>

    <!-- Contenuto Termini e Condizioni -->
    <div class="legal-content-mobile">
        <h1 class="legal-title-mobile">Termini e Condizioni d'Uso</h1>
        <p class="legal-subtitle-mobile">Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>

        <h2>1. Accettazione dei Termini</h2>
        <p>
            L'accesso e l'utilizzo del sito web <strong>www.coralplant.it</strong> (di seguito "Sito") comporta l'accettazione
            dei presenti Termini e Condizioni d'Uso. Se non si accettano tali condizioni, si prega di non utilizzare il Sito.
        </p>

        <h2>2. Oggetto del Sito</h2>
        <p>
            Il Sito è gestito da <strong>Coral Plant Srl di Angelo D'apuzzo</strong> (P.IVA: 09087111218),
            azienda agricola specializzata nella produzione e commercializzazione di giovani piante da reddito,
            con sede a Torre del Greco (NA), Italia.
        </p>
        <p>
            Il Sito ha finalità esclusivamente informative e promozionali, offrendo agli utenti:
        </p>
        <ul>
            <li>Informazioni sui prodotti (piante ornamentali e colture agricole)</li>
            <li>Storia e valori dell'azienda</li>
            <li>Contatti per richieste commerciali</li>
        </ul>
        <p>
            <strong>Il Sito non offre servizi di e-commerce diretto.</strong> Le transazioni commerciali avvengono
            esclusivamente attraverso contatti diretti (email, telefono) con l'azienda.
        </p>

        <h2>3. Diritti di Proprietà Intellettuale</h2>
        <p>
            Tutti i contenuti presenti sul Sito, inclusi ma non limitati a testi, immagini, fotografie, grafica,
            loghi, icone, video, software e database, sono di proprietà esclusiva di Coral Plant Srl o dei rispettivi
            licenzianti e sono protetti dalle leggi italiane ed internazionali sul diritto d'autore e sulla proprietà intellettuale.
        </p>
        <p>
            È vietata qualsiasi riproduzione, distribuzione, modifica, pubblicazione o utilizzo commerciale
            dei contenuti del Sito senza il preventivo consenso scritto di Coral Plant Srl.
        </p>

        <h2>4. Utilizzo Consentito del Sito</h2>
        <p>L'utente si impegna a utilizzare il Sito in modo lecito e corretto, astenendosi da:</p>
        <ul>
            <li>Violare le leggi vigenti o i diritti di terzi</li>
            <li>Diffondere virus, malware o codice dannoso</li>
            <li>Tentare di accedere in modo non autorizzato ai sistemi informatici del Sito</li>
            <li>Utilizzare il Sito per finalità fraudolente o illecite</li>
            <li>Copiare, modificare o distribuire i contenuti del Sito senza autorizzazione</li>
            <li>Utilizzare bot, script automatizzati o strumenti di scraping</li>
        </ul>

        <h2>5. Limitazione di Responsabilità</h2>
        <p>
            Coral Plant Srl si impegna a mantenere il Sito aggiornato e funzionante, tuttavia non garantisce:
        </p>
        <ul>
            <li>L'assenza di interruzioni o errori nel funzionamento del Sito</li>
            <li>L'assenza di virus o altri componenti dannosi</li>
            <li>L'accuratezza, completezza o aggiornamento delle informazioni pubblicate</li>
        </ul>
        <p>
            Coral Plant Srl non è responsabile per:
        </p>
        <ul>
            <li>Danni diretti o indiretti derivanti dall'utilizzo o dall'impossibilità di utilizzare il Sito</li>
            <li>Perdite di dati o informazioni</li>
            <li>Contenuti di siti web di terze parti eventualmente linkati</li>
        </ul>
        <p>
            <strong>Informazioni sui prodotti:</strong> Le immagini e le descrizioni dei prodotti hanno carattere
            indicativo. Caratteristiche, disponibilità e prezzi possono variare senza preavviso. Per informazioni
            aggiornate e dettagliate, si prega di contattare direttamente l'azienda.
        </p>

        <h2>6. Link a Siti di Terze Parti</h2>
        <p>
            Il Sito può contenere link a siti web di terze parti (es. social media, fornitori).
            Coral Plant Srl non controlla né è responsabile per i contenuti, le politiche sulla privacy
            o le pratiche di siti web di terzi. L'accesso a tali siti avviene a esclusivo rischio dell'utente.
        </p>

        <h2>7. Modifiche al Sito e ai Termini</h2>
        <p>
            Coral Plant Srl si riserva il diritto di:
        </p>
        <ul>
            <li>Modificare, sospendere o interrompere il Sito, in tutto o in parte, in qualsiasi momento senza preavviso</li>
            <li>Aggiornare i presenti Termini e Condizioni senza preavviso, pubblicando la versione aggiornata su questa pagina</li>
        </ul>
        <p>
            L'uso continuato del Sito dopo la pubblicazione delle modifiche costituisce accettazione dei nuovi Termini.
        </p>

        <h2>8. Privacy e Protezione dei Dati</h2>
        <p>
            Il trattamento dei dati personali degli utenti è disciplinato dalla
            <a href="mobile-privacy.php">Privacy Policy</a>, che costituisce parte integrante dei presenti Termini e Condizioni.
        </p>

        <h2>9. Contatti Commerciali</h2>
        <p>
            Per richiedere informazioni sui prodotti, preventivi o ordini, è possibile contattare:
        </p>
        <p>
            <strong>Coral Plant Srl</strong><br>
            Email: <a href="mailto:info@coralplant.it">info@coralplant.it</a><br>
            Tel: 081 883 17 02<br>
            Orari: Lun-Ven 07:30-13:00 / 15:00-17:00 | Sab 07:30-13:30
        </p>
        <p>
            <strong>Nota:</strong> Le richieste di informazione tramite email o telefono non costituiscono
            impegno contrattuale da parte dell'utente o dell'azienda fino alla conferma formale dell'ordine.
        </p>

        <h2>10. Legge Applicabile e Foro Competente</h2>
        <p>
            I presenti Termini e Condizioni sono regolati dalla legge italiana.
        </p>
        <p>
            Per qualsiasi controversia relativa all'interpretazione, esecuzione o validità dei presenti Termini,
            sarà competente in via esclusiva il Foro di Napoli, salvo diversa disposizione inderogabile di legge.
        </p>

        <h2>11. Clausola di Salvaguardia</h2>
        <p>
            Qualora una o più disposizioni dei presenti Termini risultino invalide o inefficaci, ciò non pregiudicherà
            la validità delle restanti disposizioni, che rimarranno pienamente efficaci.
        </p>

        <h2>12. Informazioni Aziendali</h2>
        <p>
            <strong>Denominazione sociale:</strong> Coral Plant Srl di Angelo D'apuzzo<br>
            <strong>Sede legale:</strong> Torre del Greco (NA), Italia<br>
            <strong>Partita IVA:</strong> 09087111218<br>
            <strong>Email:</strong> <a href="mailto:info@coralplant.it">info@coralplant.it</a><br>
            <strong>Telefono:</strong> 081 883 17 02<br>
            <strong>Settore:</strong> Azienda agricola - Produzione di giovani piante da reddito
        </p>

        <h2>13. Contatti per Informazioni</h2>
        <p>
            Per qualsiasi domanda o chiarimento relativo ai presenti Termini e Condizioni, è possibile scrivere a:
            <a href="mailto:info@coralplant.it">info@coralplant.it</a>
        </p>
    </div>

    <!-- Footer aziendale -->
    <footer class="company-footer">
        <p><strong>Coral Plant Srl</strong> | P.IVA: 09087111218</p>
        <p>&copy; <?php echo date('Y'); ?> Tutti i diritti riservati</p>
        <div class="social-icons">
            <a href="https://www.facebook.com/p/Azienda-Agricola-Coral-Plant-100063724861260/" aria-label="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="https://www.instagram.com/coral.plant/" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
        </div>
    </footer>
</body>
</html>
