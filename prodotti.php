<?php
/**
 * Pagina prodotti
 */

// Include la configurazione del database
require_once 'config.php';

// Imposta i parametri della pagina
$page_title = 'Prodotti - Coral Plant';
$additional_css = ['web/product-styles.css'];
$additional_js = ['web/products.js'];

// Ottieni tutte le specie e le relative varietà per la sidebar
$specie_query = "SELECT id, nome FROM specie ORDER BY nome";
$specie_result = $conn->query($specie_query);

$specie = [];
if ($specie_result && $specie_result->num_rows > 0) {
    while ($row = $specie_result->fetch_assoc()) {
        $specie_id = $row['id'];
        
        // Per ogni specie, ottieni le sue varietà
        $varieta_query = "SELECT id, nome FROM varieta WHERE specie_id = $specie_id ORDER BY nome";
        $varieta_result = $conn->query($varieta_query);
        
        $varieta = [];
        if ($varieta_result && $varieta_result->num_rows > 0) {
            while ($varieta_row = $varieta_result->fetch_assoc()) {
                $varieta[] = $varieta_row;
            }
        }
        
        // Aggiungi la specie all'array solo se ha varietà
        if (count($varieta) > 0) {
            $specie[] = [
                'id' => $row['id'],
                'nome' => $row['nome'],
                'varieta' => $varieta
            ];
        }
    }
}

// Gestisci il filtro per varietà
$filtro_varieta_id = isset($_GET['varieta']) ? intval($_GET['varieta']) : 0;
$filtro_specie_id = isset($_GET['specie']) ? intval($_GET['specie']) : 0;

// Se è selezionata una varietà ma non una specie, determina la specie dalla varietà
if ($filtro_varieta_id > 0 && $filtro_specie_id == 0) {
    $specie_from_varieta_query = "SELECT specie_id FROM varieta WHERE id = $filtro_varieta_id";
    $specie_from_varieta_result = $conn->query($specie_from_varieta_query);
    if ($specie_from_varieta_result && $specie_from_varieta_result->num_rows > 0) {
        $specie_from_varieta_row = $specie_from_varieta_result->fetch_assoc();
        $filtro_specie_id = $specie_from_varieta_row['specie_id'];
    }
}

// MODIFICA: Imposta show_all=1 di default se non ci sono altri filtri attivi
$show_all = isset($_GET['show_all']) ? $_GET['show_all'] == 1 : true;

// LOGICA COME NEL MOBILE: Carica le specie con immagini e conteggio per le card
$specie_list = [];
$query_specie = "
    SELECT s.id, s.nome, COUNT(DISTINCT fp.id) as num_prodotti
    FROM specie s
    LEFT JOIN varieta v ON v.specie_id = s.id
    LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
    GROUP BY s.id
    ORDER BY s.nome";

$result_specie = $conn->query($query_specie);
if ($result_specie && $result_specie->num_rows > 0) {
    while ($row = $result_specie->fetch_assoc()) {
        // Ottieni un'immagine rappresentativa per questa specie
        $img_query = "
            SELECT fp.nome_file
            FROM foto_piante fp
            JOIN varieta v ON fp.varieta_id = v.id
            WHERE v.specie_id = {$row['id']} AND fp.is_principale = 1
            LIMIT 1";
        $img_result = $conn->query($img_query);
        if ($img_result && $img_result->num_rows > 0) {
            $img_row = $img_result->fetch_assoc();
            $row['immagine'] = $img_row['nome_file'];
        } else {
            // Fallback: prendi qualsiasi immagine
            $img_query_fallback = "
                SELECT fp.nome_file
                FROM foto_piante fp
                JOIN varieta v ON fp.varieta_id = v.id
                WHERE v.specie_id = {$row['id']}
                LIMIT 1";
            $img_result_fallback = $conn->query($img_query_fallback);
            if ($img_result_fallback && $img_result_fallback->num_rows > 0) {
                $img_row_fallback = $img_result_fallback->fetch_assoc();
                $row['immagine'] = $img_row_fallback['nome_file'];
            } else {
                $row['immagine'] = null;
            }
        }
        $specie_list[] = $row;
    }
}

// Determina cosa mostrare
$mostra_specie = false;
$mostra_varieta = false;
$mostra_prodotti = false;

if ($filtro_specie_id == 0 && $filtro_varieta_id == 0) {
    // Nessun filtro: mostra le card delle specie
    $mostra_specie = true;
} elseif ($filtro_specie_id > 0 && $filtro_varieta_id == 0) {
    // Filtro specie ma non varietà: mostra le varietà di quella specie
    $mostra_varieta = true;
} else {
    // Filtro varietà: mostra i prodotti
    $mostra_prodotti = true;
}

// CARICA LE VARIETÀ se necessario
$varieta_list = [];
if ($mostra_varieta) {
    $query_varieta = "
        SELECT v.id, v.nome, COUNT(DISTINCT fp.id) as num_prodotti
        FROM varieta v
        LEFT JOIN foto_piante fp ON fp.varieta_id = v.id
        WHERE v.specie_id = $filtro_specie_id
        GROUP BY v.id
        ORDER BY v.nome";

    $result_varieta = $conn->query($query_varieta);
    if ($result_varieta && $result_varieta->num_rows > 0) {
        while ($row = $result_varieta->fetch_assoc()) {
            // Ottieni un'immagine rappresentativa per questa varietà
            $img_query = "SELECT nome_file FROM foto_piante WHERE varieta_id = {$row['id']} AND is_principale = 1 LIMIT 1";
            $img_result = $conn->query($img_query);
            if ($img_result && $img_result->num_rows > 0) {
                $img_row = $img_result->fetch_assoc();
                $row['immagine'] = $img_row['nome_file'];
            } else {
                // Fallback: prendi qualsiasi immagine
                $img_query_fallback = "SELECT nome_file FROM foto_piante WHERE varieta_id = {$row['id']} LIMIT 1";
                $img_result_fallback = $conn->query($img_query_fallback);
                if ($img_result_fallback && $img_result_fallback->num_rows > 0) {
                    $img_row_fallback = $img_result_fallback->fetch_assoc();
                    $row['immagine'] = $img_row_fallback['nome_file'];
                } else {
                    $row['immagine'] = null;
                }
            }
            $varieta_list[] = $row;
        }
    }
}

// CARICA I PRODOTTI solo se necessario
$prodotti = [];
if ($mostra_prodotti) {
    $prodotti_query = "
        SELECT fp.*, v.nome as varieta_nome, s.nome as specie_nome
        FROM foto_piante fp
        JOIN varieta v ON fp.varieta_id = v.id
        JOIN specie s ON v.specie_id = s.id
        WHERE fp.varieta_id = $filtro_varieta_id
        ORDER BY fp.data_upload ASC";

    $prodotti_result = $conn->query($prodotti_query);

    if ($prodotti_result && $prodotti_result->num_rows > 0) {
        while ($row = $prodotti_result->fetch_assoc()) {
            $prodotti[] = $row;
        }
    }
}

// Ottieni il nome della specie o varietà filtrata per il titolo
$filtro_titolo = "Tutti i Prodotti";
if ($filtro_varieta_id > 0) {
    $nome_varieta_query = "SELECT v.nome, s.nome as specie_nome 
                          FROM varieta v 
                          JOIN specie s ON v.specie_id = s.id
                          WHERE v.id = $filtro_varieta_id";
    $nome_result = $conn->query($nome_varieta_query);
    if ($nome_result && $nome_result->num_rows > 0) {
        $nome_row = $nome_result->fetch_assoc();
        $filtro_titolo = $nome_row['specie_nome'] . " - " . $nome_row['nome'];
    }
} elseif ($filtro_specie_id > 0) {
    $nome_specie_query = "SELECT nome FROM specie WHERE id = $filtro_specie_id";
    $nome_result = $conn->query($nome_specie_query);
    if ($nome_result && $nome_result->num_rows > 0) {
        $nome_row = $nome_result->fetch_assoc();
        $filtro_titolo = $nome_row['nome'];
    }
} elseif ($show_all && $filtro_varieta_id == 0 && $filtro_specie_id == 0) {
    // MODIFICA: Titolo per la pagina quando mostra il catalogo completo ma limitato
    $filtro_titolo = "Catalogo Prodotti";
}

// Include l'header
include 'web/header.php';
?>

  <!-- Main Container -->
  <div class="main-container">
    <!-- Sidebar per i filtri -->
    <!-- Modifica della struttura della sidebar in prodotti.php -->
<!-- Questa è la sezione che va sostituita nel file prodotti.php -->

<aside class="sidebar">
  <h2>Filtri</h2>
  
  <!-- Visualizza tutto il catalogo - area cliccabile migliorata -->
  <a href="prodotti.php?show_all=1" class="view-all-filters <?php echo $show_all ? 'active' : ''; ?>">
    <i class="fas fa-th"></i> <span>Visualizza catalogo completo</span>
  </a>
  
  <!-- Filtri per specie e varietà con area cliccabile migliorata -->
  <?php foreach($specie as $s): ?>
    <div class="filter-group">
      <!-- Area intera cliccabile per la categoria -->
      <div class="filter-category <?php echo ($filtro_specie_id == $s['id'] && $filtro_varieta_id == 0) ? 'active' : ''; ?>"
           data-url="prodotti.php?specie=<?php echo $s['id']; ?>">
        <span class="filter-category-text"><?php echo htmlspecialchars($s['nome']); ?></span>
        <span class="filter-icon <?php echo (($filtro_specie_id == $s['id'] && $filtro_varieta_id == 0) || in_array($filtro_varieta_id, array_column($s['varieta'], 'id'))) ? 'expanded' : ''; ?>">
          <i class="fas fa-chevron-right"></i>
        </span>
      </div>
      <ul class="filter-items <?php echo (($filtro_specie_id == $s['id'] && $filtro_varieta_id == 0) || in_array($filtro_varieta_id, array_column($s['varieta'], 'id'))) ? 'expanded' : ''; ?>">
        <?php foreach($s['varieta'] as $v): ?>
          <!-- Area intera cliccabile per ogni varietà -->
          <li class="filter-item <?php echo ($filtro_varieta_id == $v['id']) ? 'active' : ''; ?>"
              data-url="prodotti.php?varieta=<?php echo $v['id']; ?>" 
              data-variety-id="<?php echo $v['id']; ?>"
              data-variety-name="<?php echo htmlspecialchars($v['nome']); ?>">
            <span class="filter-item-text"><?php echo htmlspecialchars($v['nome']); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endforeach; ?>
</aside>

    <!-- Products Grid -->
    <main class="products-container">
      <!-- Mobile Filter Button -->
      <button class="mobile-filter-toggle">
        <i class="fas fa-filter"></i> Mostra Filtri
      </button>
      
      <div class="products-header">
        <h1 class="products-title"><?php echo htmlspecialchars($filtro_titolo); ?></h1>
        <p class="products-subtitle">La nostra selezione di piante di alta qualità</p>
      </div>

      <?php if ($mostra_specie): ?>
        <!-- Mostra le card delle specie (come nel mobile) -->
        <?php if (empty($specie_list)): ?>
          <div class="no-products">
            <h3>Nessuna categoria trovata</h3>
            <p>Non ci sono categorie disponibili.</p>
          </div>
        <?php else: ?>
          <div class="products-grid">
            <?php foreach($specie_list as $index => $specie_item): ?>
              <div class="product-card species-card" data-index="<?php echo $index; ?>" onclick="window.location.href='prodotti.php?specie=<?php echo $specie_item['id']; ?>'">
                <div class="product-image-container">
                  <?php if ($specie_item['immagine']): ?>
                    <img src="admin/uploads/piante/<?php echo htmlspecialchars($specie_item['immagine']); ?>" alt="<?php echo htmlspecialchars($specie_item['nome']); ?>" class="product-image">
                  <?php else: ?>
                    <div class="placeholder-image">
                      <i class="fas fa-leaf" style="font-size: 4rem; color: var(--olivine);"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="product-info">
                  <h3 class="product-title"><?php echo htmlspecialchars($specie_item['nome']); ?></h3>
                  <p class="product-category">
                    <i class="fas fa-leaf"></i>
                    Specie
                  </p>
                  <p class="product-description">
                    <?php echo $specie_item['num_prodotti']; ?> prodott<?php echo $specie_item['num_prodotti'] == 1 ? 'o' : 'i'; ?> disponibil<?php echo $specie_item['num_prodotti'] == 1 ? 'e' : 'i'; ?>
                  </p>
                  <div class="product-action">
                    <button class="view-details-btn">
                      <i class="fas fa-arrow-right"></i> Vedi varietà
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php elseif ($mostra_varieta): ?>
        <!-- Mostra le varietà come categorie (come nel mobile) -->
        <?php if (empty($varieta_list)): ?>
          <div class="no-products">
            <h3>Nessuna varietà trovata</h3>
            <p>Non ci sono varietà disponibili per questa specie.</p>
            <a href="prodotti.php" class="back-btn"><i class="fas fa-arrow-left"></i> Torna a tutte le categorie</a>
          </div>
        <?php else: ?>
          <div class="products-grid">
            <?php foreach($varieta_list as $index => $varieta): ?>
              <div class="product-card variety-card" data-index="<?php echo $index; ?>" onclick="window.location.href='prodotti.php?varieta=<?php echo $varieta['id']; ?>'">
                <div class="product-image-container">
                  <?php if ($varieta['immagine']): ?>
                    <img src="admin/uploads/piante/<?php echo htmlspecialchars($varieta['immagine']); ?>" alt="<?php echo htmlspecialchars($varieta['nome']); ?>" class="product-image">
                  <?php else: ?>
                    <div class="placeholder-image">
                      <i class="fas fa-leaf" style="font-size: 4rem; color: var(--olivine);"></i>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="product-info">
                  <h3 class="product-title"><?php echo htmlspecialchars($varieta['nome']); ?></h3>
                  <p class="product-category">
                    <i class="fas fa-spa"></i>
                    Varietà
                  </p>
                  <p class="product-description">
                    <?php echo $varieta['num_prodotti']; ?> prodott<?php echo $varieta['num_prodotti'] == 1 ? 'o' : 'i'; ?> disponibil<?php echo $varieta['num_prodotti'] == 1 ? 'e' : 'i'; ?>
                  </p>
                  <div class="product-action">
                    <button class="view-details-btn">
                      <i class="fas fa-arrow-right"></i> Vedi prodotti
                    </button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      <?php elseif (empty($prodotti)): ?>
        <div class="no-products">
          <h3>Nessun prodotto trovato</h3>
          <p>Al momento non ci sono prodotti disponibili per questa selezione.</p>
          <a href="prodotti.php" class="back-btn"><i class="fas fa-arrow-left"></i> Torna a tutte le categorie</a>
        </div>
      <?php else: ?>
        <div class="products-grid">
          <?php foreach($prodotti as $index => $p): ?>
            <div class="product-card" data-index="<?php echo $index; ?>">
              <div class="product-image-container">
                <img src="admin/uploads/piante/<?php echo htmlspecialchars($p['nome_file']); ?>" alt="<?php echo htmlspecialchars($p['titolo']); ?>" class="product-image">
              </div>
              <div class="product-info">
                <h3 class="product-title"><?php echo htmlspecialchars($p['titolo']); ?></h3>
                <p class="product-category">
                  <i class="fas fa-seedling"></i>
                  <?php echo htmlspecialchars($p['specie_nome']); ?> &gt; <?php echo htmlspecialchars($p['varieta_nome']); ?>
                </p>
                <p class="product-description"><?php echo htmlspecialchars(substr($p['descrizione'], 0, 150)); ?><?php echo (strlen($p['descrizione']) > 150 ? '...' : ''); ?></p>
                <div class="product-action">
                  <button class="view-details-btn" data-product-index="<?php echo $index; ?>">
                    <i class="fas fa-search-plus"></i> Scopri di più
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </main>
  </div>

<!-- Questo codice deve essere inserito al posto della modale esistente in prodotti.php -->

<!-- Modal overlay per i dettagli prodotto (versione migliorata) -->
<div id="product-modal" class="product-modal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="modal-title"></h2>
            <button class="modal-close"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-content">
            <div class="modal-image-container">
                <div class="image-slider">
                    <img id="modal-image" src="" alt="">
                    <!-- Aggiunta frecce di navigazione per le immagini multiple -->
                    <button class="slider-nav prev-image" aria-label="Immagine precedente">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider-nav next-image" aria-label="Immagine successiva">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="slider-controls">
                    <!-- I puntini per la navigazione saranno generati dinamicamente -->
                    <button class="slider-dot active" data-index="0"></button>
                </div>
            </div>
            <div class="modal-details">
                <div class="modal-info-row" id="modal-specie-row">
                    <div class="info-label"><i class="fas fa-seedling"></i> Specie:</div>
                    <div class="info-value" id="modal-specie-value"></div>
                </div>
                
                <div class="modal-info-row" id="modal-varieta-row">
                    <div class="info-label"><i class="fas fa-spa"></i> Varietà:</div>
                    <div class="info-value" id="modal-varieta-value"></div>
                </div>
                
                <div class="modal-description-container">
                    <h3 class="modal-section-title">
                        <i class="fas fa-info-circle"></i> Descrizione
                    </h3>
                    <div class="modal-description" id="modal-description"></div>
                </div>
                
                <div class="modal-actions">
                    <a href="index.php#contatti" class="modal-action-btn">
                        <i class="fas fa-envelope"></i> Richiedi informazioni
                    </a>
                    <button class="modal-close-btn">
                        <i class="fas fa-arrow-left"></i> Torna ai prodotti
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- Script per passare i dati dei prodotti al JavaScript -->
  <script type="text/javascript">
    var prodottiData = <?php echo json_encode($prodotti); ?>;
  </script>

<?php
// Chiudi la connessione al database
$conn->close();

// Include il footer
include 'web/footer.php';
?>