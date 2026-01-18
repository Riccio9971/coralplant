<?php
/**
 * Pagina Privacy Policy mobile per Coral Plant
 */

// Impostazioni della pagina
$page_title = 'Privacy Policy - Coral Plant Mobile';
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
            font-size: 1.2rem;
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
            font-size: 1.8rem;
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
        <h1 class="header-title">Privacy Policy</h1>
    </header>

    <!-- Contenuto Privacy Policy -->
    <div class="legal-content-mobile">
        <h1 class="legal-title-mobile">Privacy Policy</h1>
        <p class="legal-subtitle-mobile">Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>

        <h2>1. Titolare del Trattamento dei Dati</h2>
        <p>
            Il Titolare del trattamento dei dati è:<br>
            <strong>Coral Plant Srl di Angelo D'apuzzo</strong><br>
            Sede legale: Torre del Greco (NA), Italia<br>
            P.IVA: 09087111218<br>
            Email: <a href="mailto:info@coralplant.it">info@coralplant.it</a><br>
            Tel: 081 883 17 02
        </p>

        <h2>2. Tipologie di Dati Raccolti</h2>
        <p>
            Il presente sito web è di natura prevalentemente informativa e raccoglie una quantità minima di dati personali.
            Le informazioni trattate sono limitate a:
        </p>
        <ul>
            <li><strong>Dati di navigazione:</strong> indirizzo IP, tipo di browser, sistema operativo, orario di accesso e pagine visitate, raccolti automaticamente dai sistemi informatici.</li>
            <li><strong>Parametri URL:</strong> preferenze di visualizzazione (mobile/desktop) e filtri di ricerca prodotti (specie, varietà), utilizzati esclusivamente per migliorare l'esperienza utente.</li>
        </ul>
        <p>
            <strong>Il sito NON utilizza:</strong>
        </p>
        <ul>
            <li>Cookie di profilazione o tracciamento</li>
            <li>Strumenti di analisi come Google Analytics</li>
            <li>Form di contatto che raccolgono dati personali (l'utente può contattarci tramite i recapiti pubblicati)</li>
        </ul>

        <h2>3. Finalità del Trattamento</h2>
        <p>I dati raccolti automaticamente sono utilizzati esclusivamente per:</p>
        <ul>
            <li>Garantire il corretto funzionamento del sito web</li>
            <li>Ottimizzare la visualizzazione (versione mobile/desktop)</li>
            <li>Gestire eventuali problematiche tecniche</li>
            <li>Adempiere a obblighi di legge in materia di sicurezza informatica</li>
        </ul>

        <h2>4. Base Giuridica del Trattamento</h2>
        <p>
            Il trattamento dei dati si basa sul legittimo interesse del Titolare (art. 6, par. 1, lett. f) del GDPR)
            a garantire il funzionamento e la sicurezza del sito web, nonché sull'adempimento di obblighi legali.
        </p>

        <h2>5. Modalità di Trattamento</h2>
        <p>
            I dati sono trattati con strumenti informatici e telematici, con logiche strettamente correlate alle finalità indicate
            e con modalità atte a garantirne la sicurezza e la riservatezza.
        </p>

        <h2>6. Comunicazione e Diffusione dei Dati</h2>
        <p>
            I dati personali non vengono comunicati a terzi, né diffusi pubblicamente. Possono essere condivisi esclusivamente con:
        </p>
        <ul>
            <li>Fornitori di servizi tecnici (hosting, manutenzione) in qualità di responsabili del trattamento</li>
            <li>Autorità competenti in caso di richieste legittime per obblighi di legge</li>
        </ul>

        <h2>7. Trasferimento dei Dati</h2>
        <p>
            I dati sono conservati su server ubicati all'interno dell'Unione Europea.
            Non vengono effettuati trasferimenti di dati verso paesi terzi al di fuori dello Spazio Economico Europeo (SEE).
        </p>

        <h2>8. Periodo di Conservazione</h2>
        <p>
            I dati di navigazione sono conservati per il tempo strettamente necessario alle finalità per cui sono raccolti
            (generalmente non oltre 7 giorni, salvo necessità tecniche o obblighi legali).
        </p>

        <h2>9. Diritti dell'Interessato</h2>
        <p>
            Ai sensi degli articoli 15-22 del GDPR, l'utente ha il diritto di:
        </p>
        <ul>
            <li>Accedere ai propri dati personali</li>
            <li>Richiedere la rettifica di dati inesatti</li>
            <li>Richiedere la cancellazione dei dati (diritto all'oblio)</li>
            <li>Richiedere la limitazione del trattamento</li>
            <li>Opporsi al trattamento</li>
            <li>Richiedere la portabilità dei dati</li>
            <li>Proporre reclamo all'Autorità di controllo (Garante per la Protezione dei Dati Personali - www.garanteprivacy.it)</li>
        </ul>
        <p>
            Per esercitare tali diritti, è possibile contattare il Titolare all'indirizzo email:
            <a href="mailto:info@coralplant.it">info@coralplant.it</a>
        </p>

        <h2>10. Cookie</h2>
        <p>
            Il sito utilizza esclusivamente cookie tecnici strettamente necessari al funzionamento del sito web,
            che non richiedono il consenso dell'utente ai sensi della normativa vigente.
            Non vengono utilizzati cookie di profilazione o di terze parti per finalità di marketing.
        </p>

        <h2>11. Modifiche alla Privacy Policy</h2>
        <p>
            Il Titolare si riserva il diritto di modificare la presente Privacy Policy in qualsiasi momento.
            Le modifiche saranno comunicate attraverso la pubblicazione della versione aggiornata su questa pagina,
            con indicazione della data di ultimo aggiornamento.
        </p>

        <h2>12. Contatti</h2>
        <p>
            Per qualsiasi informazione o richiesta relativa al trattamento dei dati personali, è possibile contattare:
        </p>
        <p>
            <strong>Coral Plant Srl</strong><br>
            Email: <a href="mailto:info@coralplant.it">info@coralplant.it</a><br>
            Tel: 081 883 17 02
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
