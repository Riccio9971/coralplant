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
$page_title = 'Privacy Policy - Coral Plant';
$additional_css = ['web/legal-pages.css'];
$additional_js = [];

// Includi l'header
include 'web/header.php';
?>

<!-- Contenuto Privacy Policy -->
<section class="legal-section">
  <div class="legal-container">
    <h1 class="legal-title">Privacy Policy</h1>
    <p class="legal-subtitle">Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>

    <div class="legal-content">
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
  </div>
</section>

<?php
// Includi il footer
include 'web/footer.php';
?>
