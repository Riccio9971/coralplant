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
$page_title = 'Termini e Condizioni - Coral Plant';
$additional_css = ['web/legal-pages.css'];
$additional_js = [];

// Includi l'header
include 'web/header.php';
?>

<!-- Contenuto Termini e Condizioni -->
<section class="legal-section">
  <div class="legal-container">
    <h1 class="legal-title">Termini e Condizioni d'Uso</h1>
    <p class="legal-subtitle">Ultimo aggiornamento: <?php echo date('d/m/Y'); ?></p>

    <div class="legal-content">
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
        <a href="privacy.php">Privacy Policy</a>, che costituisce parte integrante dei presenti Termini e Condizioni.
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
  </div>
</section>

<?php
// Includi il footer
include 'web/footer.php';
?>
